<?php

namespace App\Http\Controllers;

use App\Models\DuplicateCandidate;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DuplicateCandidateController extends Controller
{
    public function index()
    {
        $duplicates = DuplicateCandidate::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'primaryRow', 'duplicateRow'])
            ->latest()
            ->get();

        return view('duplicates.index', ['duplicates' => $duplicates]);
    }

    public function fix(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        $duplicateCandidate->duplicateRow->update(['is_active' => false]);
        $duplicateCandidate->update(['status' => 'fixed']);

        $analysisService->analyze($duplicateCandidate->dataset, 'duplicate_resolution');

        return redirect('/duplicates')->with('success', 'Дубликат исключен из активного набора и проверка перезапущена.');
    }

    public function ignore(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        $duplicateCandidate->update(['status' => 'ignored']);
        $analysisService->refreshDatasetSummary($duplicateCandidate->dataset);

        return redirect('/duplicates')->with('success', 'Кандидат в дубликаты проигнорирован.');
    }
}
