<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DuplicateCandidate;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DuplicateCandidateController extends Controller
{
    public function index(Request $request)
    {
        $selectedDataset = null;
        $datasetId = (int) $request->integer('dataset');

        $duplicateQuery = DuplicateCandidate::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'primaryRow', 'duplicateRow'])
            ->when($datasetId > 0, function ($query) use ($datasetId, &$selectedDataset) {
                $selectedDataset = Dataset::query()
                    ->whereKey($datasetId)
                    ->where('user_id', Auth::id())
                    ->first();

                if ($selectedDataset) {
                    $query->where('dataset_id', $selectedDataset->id);
                }
            })
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
            'filters' => $request->only(['q', 'status', 'sort', 'dataset']),
            'selectedDataset' => $selectedDataset,
        ]);
    }

    public function fix(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        if ($duplicateCandidate->duplicateRow) {
            $duplicateCandidate->duplicateRow->update(['is_active' => false]);
        }

        $duplicateCandidate->update(['status' => 'fixed']);
        $analysisService->refreshDatasetSummary($duplicateCandidate->dataset);

        return back()->with('success', 'Повторная строка исключена из таблицы.');
    }

    public function ignore(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        $duplicateCandidate->update(['status' => 'ignored']);
        $analysisService->refreshDatasetSummary($duplicateCandidate->dataset);

        return back()->with('success', 'Повтор отмечен как пропущенный.');
    }

    public function fixGroup(DuplicateCandidate $duplicateCandidate, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $duplicateCandidate->dataset);

        $groupDuplicates = DuplicateCandidate::query()
            ->where('dataset_id', $duplicateCandidate->dataset_id)
            ->where('primary_row_id', $duplicateCandidate->primary_row_id)
            ->where('status', 'open')
            ->with('duplicateRow')
            ->get();

        if ($groupDuplicates->isEmpty()) {
            return back()->with('error', 'Открытых повторов для этой строки уже не осталось.');
        }

        DB::transaction(function () use ($groupDuplicates) {
            foreach ($groupDuplicates as $groupDuplicate) {
                if ($groupDuplicate->duplicateRow) {
                    $groupDuplicate->duplicateRow->update(['is_active' => false]);
                }

                $groupDuplicate->update(['status' => 'fixed']);
            }
        });

        $analysisService->refreshDatasetSummary($duplicateCandidate->dataset);

        return back()->with('success', 'Все повторы для этой строки удалены.');
    }
}
