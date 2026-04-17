<?php

namespace App\Http\Controllers;

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

    public function fix(Issue $issue, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $issue->dataset);

        if (! $issue->suggested_value || ! $issue->datasetRow || ! $issue->column_name) {
            return back()->with('error', 'Для этого инцидента нет безопасного regex-исправления.');
        }

        $payload = $issue->datasetRow->payload;
        $payload[$issue->column_name] = $issue->suggested_value;

        $issue->datasetRow->update(['payload' => $payload]);
        $issue->update(['status' => 'fixed']);

        $analysisService->analyze($issue->dataset, 'regex_fix');

        return back()->with('success', 'Значение исправлено и набор перепроверен.');
    }

    public function ignore(Issue $issue, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $issue->dataset);

        $issue->update(['status' => 'ignored']);
        $analysisService->refreshDatasetSummary($issue->dataset);

        return back()->with('success', 'Инцидент помечен как проигнорированный.');
    }
}
