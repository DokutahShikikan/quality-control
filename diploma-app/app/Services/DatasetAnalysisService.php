<?php

namespace App\Services;

use App\Models\CheckRun;
use App\Models\Dataset;
use App\Models\DatasetRow;
use App\Models\DuplicateCandidate;
use App\Models\Issue;
use App\Models\QualityRule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Throwable;

class DatasetAnalysisService
{
    public function analyze(Dataset $dataset, string $triggerSource = 'manual'): CheckRun
    {
        $dataset->loadMissing('activeRows');

        $dataset->issues()->delete();
        $dataset->duplicateCandidates()->delete();

        $rows = $dataset->activeRows()->orderBy('row_index')->get();
        $rules = QualityRule::query()->where('is_active', true)->get();

        $checkRun = $dataset->checkRuns()->create([
            'status' => 'running',
            'trigger_source' => $triggerSource,
            'total_rows' => $rows->count(),
            'started_at' => now(),
        ]);

        $issuesCount = 0;
        $duplicatePairsCount = 0;

        foreach ($rows as $row) {
            $issuesCount += $this->detectMissingValues($dataset, $checkRun, $row);
            $issuesCount += $this->detectRegexIssues($dataset, $checkRun, $row, $rules);
            $this->refreshFingerprint($row);
        }

        $duplicatePairsCount = $this->detectDuplicates($dataset, $checkRun, $rows);

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

    private function detectMissingValues(Dataset $dataset, CheckRun $checkRun, DatasetRow $row): int
    {
        $created = 0;

        foreach ($row->payload as $column => $value) {
            if ($value !== null && trim((string) $value) !== '') {
                continue;
            }

            Issue::create([
                'dataset_id' => $dataset->id,
                'check_run_id' => $checkRun->id,
                'dataset_row_id' => $row->id,
                'column_name' => $column,
                'issue_type' => 'missing_value',
                'severity' => 'medium',
                'title' => 'Пустое значение',
                'message' => "В колонке \"{$column}\" отсутствует значение.",
                'original_value' => '',
                'suggested_value' => null,
                'suggestion_source' => 'regex',
                'meta' => ['row_index' => $row->row_index],
            ]);

            $created++;
        }

        return $created;
    }

    private function detectRegexIssues(Dataset $dataset, CheckRun $checkRun, DatasetRow $row, Collection $rules): int
    {
        $created = 0;

        foreach ($row->payload as $column => $value) {
            if (trim((string) $value) === '') {
                continue;
            }

            foreach ($rules as $rule) {
                if (! $this->ruleMatchesColumn($rule, $column)) {
                    continue;
                }

                if ($rule->pattern && preg_match('/'.$rule->pattern.'/u', (string) $value)) {
                    continue;
                }

                $suggested = $this->buildSuggestion($rule->name, (string) $value);

                Issue::create([
                    'dataset_id' => $dataset->id,
                    'check_run_id' => $checkRun->id,
                    'dataset_row_id' => $row->id,
                    'column_name' => $column,
                    'issue_type' => $rule->issue_type,
                    'severity' => $rule->severity,
                    'title' => $rule->name,
                    'message' => $rule->description ?: "Проверка \"{$rule->name}\" не прошла для колонки \"{$column}\".",
                    'original_value' => (string) $value,
                    'suggested_value' => $suggested,
                    'suggestion_source' => 'regex',
                    'meta' => ['row_index' => $row->row_index],
                ]);

                $created++;
            }
        }

        return $created;
    }

    private function detectDuplicates(Dataset $dataset, CheckRun $checkRun, Collection $rows): int
    {
        $groups = [];

        foreach ($rows as $row) {
            if (! $row->fingerprint) {
                continue;
            }

            $groups[$row->fingerprint][] = $row;
        }

        $created = 0;

        foreach ($groups as $group) {
            if (count($group) < 2) {
                continue;
            }

            $primaryRow = $group[0];

            foreach (array_slice($group, 1) as $duplicateRow) {
                DuplicateCandidate::create([
                    'dataset_id' => $dataset->id,
                    'check_run_id' => $checkRun->id,
                    'primary_row_id' => $primaryRow->id,
                    'duplicate_row_id' => $duplicateRow->id,
                    'confidence' => 1.00,
                    'rationale' => 'Совпадает нормализованный отпечаток строки.',
                ]);

                $created++;
            }
        }

        return $created;
    }

    private function refreshFingerprint(DatasetRow $row): void
    {
        $values = collect($row->payload)
            ->map(fn (mixed $value) => Str::of((string) $value)->trim()->lower()->replaceMatches('/\s+/u', ' ')->value())
            ->filter()
            ->values()
            ->all();

        $row->update([
            'fingerprint' => $values === [] ? null : sha1(implode('|', $values)),
        ]);
    }

    private function ruleMatchesColumn(QualityRule $rule, string $column): bool
    {
        $column = Str::lower($column);

        return collect($rule->column_hints ?? [])->contains(
            fn (string $hint) => Str::contains($column, Str::lower($hint))
        );
    }

    private function buildSuggestion(string $ruleName, string $value): ?string
    {
        return match ($ruleName) {
            'Email format' => Str::lower(trim($value)),
            'Phone format' => $this->normalizePhone($value),
            'Date format' => $this->normalizeDate($value),
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
}
