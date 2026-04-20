<?php

namespace App\Http\Controllers;

use App\Models\DuplicateCandidate;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DuplicateCandidateController extends Controller
{
    public function index(Request $request)
    {
        $duplicateQuery = DuplicateCandidate::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'primaryRow', 'duplicateRow'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->string('q'));
                $query->where(function ($inner) use ($term) {
                    $inner->where('rationale', 'like', "%{$term}%")
                        ->orWhereHas('dataset', fn ($datasetQuery) => $datasetQuery->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()));

        match ($request->string('sort')->value()) {
            'oldest' => $duplicateQuery->oldest(),
            'confidence_low' => $duplicateQuery->orderBy('confidence')->latest(),
            'confidence_high' => $duplicateQuery->orderByDesc('confidence')->latest(),
            default => $duplicateQuery->latest(),
        };

        $duplicates = $duplicateQuery->paginate(50)->withQueryString();

        return view('duplicates.index', [
            'duplicates' => $duplicates,
            'filters' => $request->only(['q', 'status', 'sort']),
        ]);
    }

    public function fix(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        $duplicateCandidate->duplicateRow->update(['is_active' => false]);
        $duplicateCandidate->update(['status' => 'fixed']);

        $analysisService->analyze($duplicateCandidate->dataset, 'duplicate_resolution');

        return back()->with('success', 'Повторная строка исключена из таблицы, и проверка запущена заново.');
    }

    public function ignore(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        $duplicateCandidate->update(['status' => 'ignored']);
        $analysisService->refreshDatasetSummary($duplicateCandidate->dataset);

        return back()->with('success', 'Повтор отмечен как пропущенный.');
    }
}
