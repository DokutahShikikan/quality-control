<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetRow;
use App\Models\Issue;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IssueController extends Controller
{
    public function index(Request $request)
    {
        $issueQuery = Issue::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'datasetRow'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->string('q'));
                $query->where(function ($inner) use ($term) {
                    $inner->where('title', 'like', "%{$term}%")
                        ->orWhere('message', 'like', "%{$term}%")
                        ->orWhere('column_name', 'like', "%{$term}%")
                        ->orWhere('original_value', 'like', "%{$term}%")
                        ->orWhere('suggested_value', 'like', "%{$term}%")
                        ->orWhereHas('dataset', fn ($datasetQuery) => $datasetQuery->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->when($request->filled('issue_type'), fn ($query) => $query->where('issue_type', $request->string('issue_type')->value()))
            ->when($request->filled('severity'), fn ($query) => $query->where('severity', $request->string('severity')->value()));

        match ($request->string('sort')->value()) {
            'oldest' => $issueQuery->oldest(),
            'severity' => $issueQuery->orderByRaw("case severity when 'high' then 1 when 'medium' then 2 else 3 end")->latest(),
            'status' => $issueQuery->orderBy('status')->latest(),
            default => $issueQuery->latest(),
        };

        $issues = $issueQuery->paginate(50)->withQueryString();

        return view('issues.index', [
            'issues' => $issues,
            'filters' => $request->only(['q', 'status', 'issue_type', 'severity', 'sort']),
        ]);
    }

    public function fix(Request $request, int $issue, DatasetAnalysisService $analysisService): RedirectResponse
    {
        $issue = $this->resolveIssue($issue);

        if (! $issue) {
            return $this->fixFromSnapshot($request, $analysisService);
        }

        Gate::authorize('update', $issue->dataset);

        if (! $issue->suggested_value || ! $issue->datasetRow || ! $issue->column_name) {
            return back()->with('error', 'Для этой ошибки нет безопасного автоматического исправления по шаблону.');
        }

        $payload = $issue->datasetRow->payload;
        $payload[$issue->column_name] = $issue->suggested_value;

        $issue->datasetRow->update(['payload' => $payload]);
        $issue->update(['status' => 'fixed']);

        $analysisService->analyze($issue->dataset, 'regex_fix');

        return back()->with('success', 'Значение исправлено, и таблица проверена заново.');
    }

    public function ignore(int $issue, DatasetAnalysisService $analysisService): RedirectResponse
    {
        $issue = $this->resolveIssue($issue);

        if (! $issue) {
            return redirect('/issues')->with('error', 'Эта ошибка уже обновилась после новой проверки. Открой свежий список и попробуй снова.');
        }

        Gate::authorize('update', $issue->dataset);

        $issue->update(['status' => 'ignored']);
        $analysisService->refreshDatasetSummary($issue->dataset);

        return back()->with('success', 'Ошибка отмечена как пропущенная.');
    }

    private function resolveIssue(int $issueId): ?Issue
    {
        return Issue::query()
            ->whereKey($issueId)
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'datasetRow'])
            ->first();
    }

    private function fixFromSnapshot(Request $request, DatasetAnalysisService $analysisService): RedirectResponse
    {
        $datasetId = (int) $request->integer('dataset_id');
        $datasetRowId = (int) $request->integer('dataset_row_id');
        $columnName = trim((string) $request->input('column_name'));
        $suggestedValue = (string) $request->input('suggested_value', '');

        if (! $datasetId || ! $datasetRowId || $columnName === '' || $suggestedValue === '') {
            return redirect('/issues')->with('error', 'Эта ошибка уже обновилась после новой проверки. Открой свежий список и попробуй снова.');
        }

        $dataset = Dataset::query()
            ->whereKey($datasetId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $dataset) {
            return redirect('/issues')->with('error', 'Таблица для этого исправления не найдена.');
        }

        Gate::authorize('update', $dataset);

        $datasetRow = DatasetRow::query()
            ->whereKey($datasetRowId)
            ->where('dataset_id', $dataset->id)
            ->first();

        if (! $datasetRow) {
            return redirect('/issues')->with('error', 'Строка уже изменилась после новой проверки. Открой свежий список и попробуй снова.');
        }

        $payload = $datasetRow->payload;
        $payload[$columnName] = $suggestedValue;

        $datasetRow->update(['payload' => $payload]);

        $analysisService->analyze($dataset, 'regex_fix');

        return redirect('/issues')->with('success', 'Значение исправлено, и таблица проверена заново.');
    }
}
