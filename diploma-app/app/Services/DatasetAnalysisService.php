<?php

namespace App\Services;

use App\Models\CheckRun;
use App\Models\Dataset;
use App\Models\DatasetRow;
use App\Models\QualityRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class DatasetAnalysisService
{
    public function analyze(Dataset $dataset, string $triggerSource = 'manual'): CheckRun
    {
        @set_time_limit(0);

        $dataset->issues()->delete();
        $dataset->duplicateCandidates()->delete();

        $rules = $this->resolveRules();

        $checkRun = $dataset->checkRuns()->create([
            'status' => 'running',
            'trigger_source' => $triggerSource,
            'total_rows' => $dataset->activeRows()->count(),
            'started_at' => now(),
        ]);

        $issuesCount = 0;
        $duplicateGroups = [];

        $dataset->activeRows()
            ->orderBy('row_index')
            ->chunk(500, function (Collection $rows) use ($dataset, $checkRun, $rules, &$issuesCount, &$duplicateGroups) {
                $issueBatch = [];
                $fingerprintUpdates = [];

                foreach ($rows as $row) {
                    $issueBatch = [
                        ...$issueBatch,
                        ...$this->buildMissingValueIssues($dataset, $checkRun, $row),
                        ...$this->buildRegexIssues($dataset, $checkRun, $row, $rules),
                    ];

                    $fingerprint = $this->buildFingerprint($row->payload);
                    $fingerprintUpdates[] = [
                        'id' => $row->id,
                        'fingerprint' => $fingerprint,
                    ];

                    if ($fingerprint) {
                        $duplicateGroups[$fingerprint][] = $row->id;
                    }
                }

                if ($issueBatch !== []) {
                    DB::table('issues')->insert($issueBatch);
                    $issuesCount += count($issueBatch);
                }

                $this->persistFingerprints($fingerprintUpdates);
            });

        $duplicatePairsCount = $this->insertDuplicateCandidates($dataset, $checkRun, $duplicateGroups);

        $checkRun->update([
            'status' => 'completed',
            'issues_count' => $issuesCount,
            'duplicate_pairs_count' => $duplicatePairsCount,
            'summary' => [
                'regex_rules_used' => $rules->count(),
                'open_issues' => $dataset->issues()->where('status', 'open')->count(),
                'open_duplicates' => $dataset->duplicateCandidates()->where('status', 'open')->count(),
            ],
            'finished_at' => now(),
        ]);

        $this->refreshDatasetSummary($dataset);

        return $checkRun->fresh();
    }

    public function refreshDatasetSummary(Dataset $dataset): void
    {
        $activeRowsCount = $dataset->activeRows()->count();
        $openIssues = $dataset->issues()->where('status', 'open')->count();
        $ignoredIssues = $dataset->issues()->where('status', 'ignored')->count();
        $fixableIssues = $dataset->issues()
            ->where('status', 'open')
            ->whereNotNull('suggested_value')
            ->count();
        $openDuplicates = $dataset->duplicateCandidates()->where('status', 'open')->count();
        $totalCells = max(1, $activeRowsCount * max(1, $dataset->total_columns));
        $missingIssues = $dataset->issues()->where('status', 'open')->where('issue_type', 'missing_value')->count();
        $formatIssues = $dataset->issues()->where('status', 'open')->where('issue_type', 'invalid_format')->count();

        $dataset->update([
            'review_status' => $openIssues || $openDuplicates ? 'needs_review' : 'clean',
            'metrics' => [
                'open_issues' => $openIssues,
                'ignored_issues' => $ignoredIssues,
                'fixable_issues' => $fixableIssues,
                'open_duplicates' => $openDuplicates,
                'completeness_rate' => round((1 - ($missingIssues / $totalCells)) * 100, 1),
                'duplicate_rate' => round(($openDuplicates / max(1, $activeRowsCount)) * 100, 1),
                'format_error_rate' => round(($formatIssues / $totalCells) * 100, 1),
                'deepseek_stage_ready' => $fixableIssues === 0 && $openIssues > 0,
            ],
            'last_checked_at' => now(),
        ]);
    }

    private function resolveRules(): Collection
    {
        $storedRules = QualityRule::query()
            ->where('is_active', true)
            ->get()
            ->map(fn (QualityRule $rule) => $this->normalizeRule([
                'name' => $rule->name,
                'issue_type' => $rule->issue_type,
                'severity' => $rule->severity,
                'description' => $rule->description,
                'pattern' => $rule->pattern,
                'column_hints' => $rule->column_hints ?? [],
            ]));

        $rulesByName = $storedRules->keyBy('name');

        foreach ($this->defaultRules() as $rule) {
            $mergedRule = array_merge($rule, $rulesByName->get($rule['name'], []));
            $mergedRule['column_hints'] = $this->normalizeColumnHints(
                $mergedRule['column_hints'] ?? $rule['column_hints']
            );

            $rulesByName->put($rule['name'], $mergedRule);
        }

        return $rulesByName->values();
    }

    private function defaultRules(): array
    {
        return [
            [
                'name' => 'Email format',
                'issue_type' => 'invalid_format',
                'severity' => 'high',
                'description' => 'Проверяет адреса электронной почты и предлагает lowercase/trim нормализацию.',
                'pattern' => '^[^\s@]+@[^\s@]+\.[^\s@]+$',
                'column_hints' => ['email', 'e-mail', 'почта'],
            ],
            [
                'name' => 'Phone format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет телефонные номера и пытается привести их к международному виду.',
                'pattern' => '^\+?\d[\d\-\(\)\s]{9,}$',
                'column_hints' => ['phone', 'tel', 'mobile', 'телефон'],
            ],
            [
                'name' => 'Date format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет даты и нормализует их к ISO-формату YYYY-MM-DD.',
                'pattern' => '^\d{4}-\d{2}-\d{2}$',
                'column_hints' => ['date', 'birth', 'created', 'дата'],
            ],
            [
                'name' => 'Numeric value',
                'issue_type' => 'invalid_format',
                'severity' => 'high',
                'description' => 'Проверяет числовые поля, например salary, amount, total, score или price.',
                'pattern' => '^-?\d+(?:[.,]\d+)?$',
                'column_hints' => ['salary', 'amount', 'price', 'cost', 'total', 'score', 'qty', 'quantity', 'sum'],
            ],
            [
                'name' => 'Status format',
                'issue_type' => 'invalid_format',
                'severity' => 'medium',
                'description' => 'Проверяет, что статус соответствует одному из допустимых значений.',
                'pattern' => '^(active|inactive|pending)$',
                'column_hints' => ['status', 'state', 'статус'],
            ],
        ];
    }

    private function buildMissingValueIssues(Dataset $dataset, CheckRun $checkRun, DatasetRow $row): array
    {
        $issues = [];

        foreach ($row->payload as $column => $value) {
            if ($value !== null && trim((string) $value) !== '') {
                continue;
            }

            $issues[] = $this->makeIssueRecord($dataset, $checkRun, $row, [
                'column_name' => $column,
                'issue_type' => 'missing_value',
                'severity' => 'medium',
                'title' => 'Пустое значение',
                'message' => "В колонке \"{$column}\" отсутствует значение.",
                'original_value' => '',
                'suggested_value' => null,
            ]);
        }

        return $issues;
    }

    private function buildRegexIssues(Dataset $dataset, CheckRun $checkRun, DatasetRow $row, Collection $rules): array
    {
        $issues = [];

        foreach ($row->payload as $column => $value) {
            $value = trim((string) $value);

            if ($value === '') {
                continue;
            }

            foreach ($rules as $rule) {
                if (! $this->ruleMatchesColumn($rule, $column)) {
                    continue;
                }

                $pattern = data_get($rule, 'pattern');

                if ($pattern && preg_match('/'.$pattern.'/u', $value)) {
                    continue;
                }

                $issues[] = $this->makeIssueRecord($dataset, $checkRun, $row, [
                    'column_name' => $column,
                    'issue_type' => data_get($rule, 'issue_type', 'invalid_format'),
                    'severity' => data_get($rule, 'severity', 'medium'),
                    'title' => data_get($rule, 'name', 'Format rule'),
                    'message' => data_get($rule, 'description') ?: "Проверка не прошла для колонки \"{$column}\".",
                    'original_value' => $value,
                    'suggested_value' => $this->buildSuggestion((string) data_get($rule, 'name'), $value),
                ]);
            }
        }

        return $issues;
    }

    private function insertDuplicateCandidates(Dataset $dataset, CheckRun $checkRun, array $duplicateGroups): int
    {
        $created = 0;
        $timestamp = now();
        $records = [];

        foreach ($duplicateGroups as $group) {
            if (count($group) < 2) {
                continue;
            }

            $primaryRowId = $group[0];

            foreach (array_slice($group, 1) as $duplicateRowId) {
                $records[] = [
                    'dataset_id' => $dataset->id,
                    'check_run_id' => $checkRun->id,
                    'primary_row_id' => $primaryRowId,
                    'duplicate_row_id' => $duplicateRowId,
                    'confidence' => 1.00,
                    'rationale' => 'Совпадает нормализованный отпечаток строки.',
                    'status' => 'open',
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];

                $created++;
            }
        }

        foreach (array_chunk($records, 500) as $chunk) {
            DB::table('duplicate_candidates')->insert($chunk);
        }

        return $created;
    }

    private function buildFingerprint(array $payload): ?string
    {
        $values = collect($payload)
            ->map(fn (mixed $value) => Str::of((string) $value)->trim()->lower()->replaceMatches('/\s+/u', ' ')->value())
            ->filter()
            ->values()
            ->all();

        return $values === [] ? null : sha1(implode('|', $values));
    }

    private function persistFingerprints(array $fingerprintUpdates): void
    {
        if ($fingerprintUpdates === []) {
            return;
        }

        $pdo = DB::connection()->getPdo();
        $cases = [];
        $ids = [];
        $timestamp = now()->toDateTimeString();

        foreach ($fingerprintUpdates as $update) {
            $id = (int) $update['id'];
            $ids[] = $id;
            $fingerprint = $update['fingerprint'];
            $quotedFingerprint = $fingerprint === null ? 'NULL' : $pdo->quote($fingerprint);
            $cases[] = "WHEN {$id} THEN {$quotedFingerprint}";
        }

        $idList = implode(',', $ids);
        $quotedTimestamp = $pdo->quote($timestamp);
        $sql = "UPDATE dataset_rows SET fingerprint = CASE id ".implode(' ', $cases)." END, updated_at = {$quotedTimestamp} WHERE id IN ({$idList})";

        DB::statement($sql);
    }

    private function ruleMatchesColumn(array $rule, string $column): bool
    {
        $column = Str::lower($column);

        return collect($this->normalizeColumnHints(data_get($rule, 'column_hints', [])))->contains(
            fn (string $hint) => Str::contains($column, Str::lower($hint))
        );
    }

    private function normalizeRule(array $rule): array
    {
        $rule['column_hints'] = $this->normalizeColumnHints($rule['column_hints'] ?? []);

        return $rule;
    }

    private function normalizeColumnHints(mixed $columnHints): array
    {
        if (is_string($columnHints)) {
            $decoded = json_decode($columnHints, true);

            if (is_array($decoded)) {
                $columnHints = $decoded;
            } else {
                $columnHints = [$columnHints];
            }
        }

        if (! is_array($columnHints)) {
            return [];
        }

        return collect($columnHints)
            ->map(fn (mixed $hint) => trim((string) $hint))
            ->filter()
            ->values()
            ->all();
    }

    private function buildSuggestion(string $ruleName, string $value): ?string
    {
        return match ($ruleName) {
            'Email format' => Str::lower(trim($value)),
            'Phone format' => $this->normalizePhone($value),
            'Date format' => $this->normalizeDate($value),
            'Numeric value' => $this->normalizeNumeric($value),
            'Status format' => $this->normalizeStatus($value),
            default => null,
        };
    }

    private function normalizePhone(string $value): ?string
    {
        $digits = preg_replace('/\D+/u', '', $value) ?? '';

        return match (strlen($digits)) {
            10 => '+7'.$digits,
            11 => '+'.$digits,
            default => null,
        };
    }

    private function normalizeDate(string $value): ?string
    {
        $value = trim($value);

        foreach (['d.m.Y', 'd/m/Y', 'Y-m-d'] as $format) {
            try {
                $date = Carbon::createFromFormat($format, $value);
            } catch (Throwable) {
                $date = false;
            }

            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        return null;
    }

    private function normalizeNumeric(string $value): ?string
    {
        $normalized = str_replace(',', '.', trim($value));

        return preg_match('/^-?\d+(?:\.\d+)?$/', $normalized) ? $normalized : null;
    }

    private function normalizeStatus(string $value): ?string
    {
        $normalized = Str::lower(trim($value));

        return in_array($normalized, ['active', 'inactive', 'pending'], true) ? $normalized : null;
    }

    private function makeIssueRecord(Dataset $dataset, CheckRun $checkRun, DatasetRow $row, array $attributes): array
    {
        $timestamp = now();

        return [
            'dataset_id' => $dataset->id,
            'check_run_id' => $checkRun->id,
            'dataset_row_id' => $row->id,
            'column_name' => $attributes['column_name'] ?? null,
            'issue_type' => $attributes['issue_type'] ?? 'invalid_format',
            'severity' => $attributes['severity'] ?? 'medium',
            'title' => $attributes['title'] ?? 'Format rule',
            'message' => $attributes['message'] ?? '',
            'original_value' => $attributes['original_value'] ?? null,
            'suggested_value' => $attributes['suggested_value'] ?? null,
            'suggestion_source' => 'regex',
            'status' => 'open',
            'meta' => json_encode(['row_index' => $row->row_index], JSON_UNESCAPED_UNICODE),
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ];
    }
}
