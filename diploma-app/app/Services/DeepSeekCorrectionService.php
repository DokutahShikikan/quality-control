<?php

namespace App\Services;

use App\Models\Dataset;
use App\Models\Issue;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class DeepSeekCorrectionService
{
    public function __construct(
        private readonly HttpFactory $http,
        private readonly DatasetAnalysisService $analysisService,
    ) {
    }

    public function isConfigured(): bool
    {
        return filled((string) config('services.deepseek.api_key'));
    }

    public function correctDataset(Dataset $dataset): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Не настроен ключ DeepSeek API.');
        }

        if (! $dataset->deepseek_enabled) {
            throw new RuntimeException('Для этой таблицы не включён шаг с ИИ.');
        }

        @set_time_limit(0);

        $issues = $dataset->issues()
            ->where('status', 'open')
            ->where(function ($query) {
                $query->whereNull('suggested_value')
                    ->orWhere('suggested_value', '');
            })
            ->with('datasetRow')
            ->get();

        $result = [
            'checked' => 0,
            'fixed' => 0,
            'skipped' => 0,
            'failed' => 0,
        ];

        foreach ($issues as $issue) {
            $result['checked']++;

            if (! $issue->datasetRow || ! $issue->column_name) {
                $result['skipped']++;
                continue;
            }

            try {
                $correction = $this->requestCorrection($dataset, $issue);
            } catch (Throwable $exception) {
                $this->storeAiMeta($issue, [
                    'ai_error' => $exception->getMessage(),
                ]);

                Log::warning('DeepSeek correction failed.', [
                    'dataset_id' => $dataset->id,
                    'issue_id' => $issue->id,
                    'error' => $exception->getMessage(),
                ]);

                $result['failed']++;
                continue;
            }

            $correctedValue = trim((string) ($correction['corrected_value'] ?? ''));
            $shouldFix = (bool) ($correction['should_fix'] ?? false);
            $originalValue = (string) ($issue->original_value ?? '');

            $this->storeAiMeta($issue, [
                'ai_reason' => $correction['reason'] ?? null,
                'ai_confidence' => $correction['confidence'] ?? null,
                'ai_model' => config('services.deepseek.model'),
            ]);

            if (! $shouldFix || $correctedValue === '' || $correctedValue === $originalValue) {
                $result['skipped']++;
                continue;
            }

            $payload = $issue->datasetRow->payload;
            $payload[$issue->column_name] = $correctedValue;

            $issue->datasetRow->update(['payload' => $payload]);
            $issue->update([
                'suggested_value' => $correctedValue,
                'suggestion_source' => 'deepseek',
                'status' => 'fixed',
            ]);

            $result['fixed']++;
        }

        if ($result['fixed'] > 0) {
            $this->analysisService->analyze($dataset->fresh(), 'deepseek_fix');
        } else {
            $this->analysisService->refreshDatasetSummary($dataset->fresh());
        }

        return $result;
    }

    private function requestCorrection(Dataset $dataset, Issue $issue): array
    {
        $response = $this->http
            ->baseUrl((string) config('services.deepseek.base_url'))
            ->withToken((string) config('services.deepseek.api_key'))
            ->acceptJson()
            ->withOptions(['verify' => (bool) config('services.deepseek.verify_ssl')])
            ->timeout(90)
            ->post('/chat/completions', [
                'model' => config('services.deepseek.model'),
                'temperature' => 0.1,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You correct data quality issues. Return only JSON with keys corrected_value, should_fix, reason, confidence. If you are unsure or the value cannot be safely restored, set should_fix to false and corrected_value to null.',
                    ],
                    [
                        'role' => 'user',
                        'content' => json_encode([
                            'dataset' => $dataset->name,
                            'headers' => $dataset->headers,
                            'row_index' => data_get($issue->meta, 'row_index'),
                            'column_name' => $issue->column_name,
                            'issue_title' => $issue->title,
                            'issue_message' => $issue->message,
                            'current_value' => $issue->original_value,
                            'row_payload' => $issue->datasetRow?->payload ?? [],
                            'requirements' => [
                                'Keep the corrected value short and plain.',
                                'Do not invent missing values if there is not enough context.',
                                'For emails, phones, dates, statuses and simple typos you may normalize the value.',
                            ],
                        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ],
                ],
            ])
            ->throw()
            ->json();

        $content = (string) data_get($response, 'choices.0.message.content', '');
        $decoded = json_decode($this->sanitizeJsonContent($content), true);

        if (! is_array($decoded)) {
            throw new RuntimeException('DeepSeek вернул некорректный ответ.');
        }

        return [
            'corrected_value' => Arr::get($decoded, 'corrected_value'),
            'should_fix' => Arr::get($decoded, 'should_fix', false),
            'reason' => Arr::get($decoded, 'reason'),
            'confidence' => Arr::get($decoded, 'confidence'),
        ];
    }

    private function sanitizeJsonContent(string $content): string
    {
        $trimmed = trim($content);

        if (str_starts_with($trimmed, '```')) {
            $trimmed = preg_replace('/^```(?:json)?\s*|\s*```$/u', '', $trimmed) ?? $trimmed;
        }

        return trim($trimmed);
    }

    private function storeAiMeta(Issue $issue, array $values): void
    {
        $meta = is_array($issue->meta) ? $issue->meta : [];
        $issue->update([
            'meta' => array_filter(array_merge($meta, $values), fn ($value) => $value !== null && $value !== ''),
        ]);
    }
}
