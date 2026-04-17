<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class IssueController extends Controller
{
    public function index()
    {
        $issues = Issue::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'datasetRow'])
            ->latest()
            ->get();

        return view('issues.index', ['issues' => $issues]);
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

        return redirect('/issues')->with('success', 'Значение исправлено и набор перепроверен.');
    }

    public function ignore(Issue $issue, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $issue->dataset);

        $issue->update(['status' => 'ignored']);
        $analysisService->refreshDatasetSummary($issue->dataset);

        return redirect('/issues')->with('success', 'Инцидент помечен как проигнорированный.');
    }
}
