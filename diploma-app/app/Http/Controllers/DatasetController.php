<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatasetRequest;
use App\Jobs\ProcessDatasetImport;
use App\Models\Dataset;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DatasetController extends Controller
{
    public function index(Request $request)
    {
        $userDatasetIds = Auth::user()->datasets()->pluck('id');
        $datasetsBaseQuery = Dataset::query()->whereIn('id', $userDatasetIds);
        $allDatasets = (clone $datasetsBaseQuery)->get();

        $datasetsQuery = (clone $datasetsBaseQuery)
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->string('q'));
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%")
                        ->orWhere('source_filename', 'like', "%{$term}%");
                });
            })
            ->when($request->filled('review_status'), fn ($query) => $query->where('review_status', $request->string('review_status')->value()));

        match ($request->string('sort')->value()) {
            'oldest' => $datasetsQuery->oldest(),
            'most_issues' => $datasetsQuery->orderByDesc('updated_at'),
            default => $datasetsQuery->latest(),
        };

        $datasets = $datasetsQuery->paginate(12)->withQueryString();

        $metrics = [
            'datasets' => $allDatasets->count(),
            'open_issues' => $allDatasets->sum(fn (Dataset $dataset) => data_get($dataset->metrics, 'open_issues', 0)),
            'open_duplicates' => $allDatasets->sum(fn (Dataset $dataset) => data_get($dataset->metrics, 'open_duplicates', 0)),
            'ready_for_ai' => $allDatasets->filter(
                fn (Dataset $dataset) => data_get($dataset->metrics, 'deepseek_stage_ready', false)
            )->count(),
        ];

        return view('datasets.index', [
            'datasets' => $datasets,
            'metrics' => $metrics,
            'filters' => $request->only(['q', 'review_status', 'sort']),
        ]);
    }

    public function create()
    {
        return view('datasets.create');
    }

    public function store(DatasetRequest $request): RedirectResponse
    {
        $file = $request->file('source_file');
        $storedFilename = now()->format('YmdHis').'-'.uniqid().'-'.$file->getClientOriginalName();
        $storedPath = $file->storeAs('imports', $storedFilename, 'local');

        $dataset = DB::transaction(function () use ($request, $file, $storedPath) {
            return Auth::user()->datasets()->create([
                'name' => $request->string('name')->value(),
                'description' => $request->string('description')->value() ?: null,
                'source_filename' => $file->getClientOriginalName(),
                'source_path' => $storedPath,
                'source_mime' => $file->getMimeType(),
                'headers' => [],
                'total_rows' => 0,
                'total_columns' => 0,
                'deepseek_enabled' => $request->boolean('deepseek_enabled'),
                'import_status' => 'queued',
                'import_error' => null,
            ]);
        });

        if (app()->environment('testing')) {
            ProcessDatasetImport::dispatchSync($dataset->id);
        } else {
            ProcessDatasetImport::dispatch($dataset->id);
        }

        return redirect("/datasets/{$dataset->id}")
            ->with('success', 'Набор поставлен в очередь на импорт. Данные и инциденты появятся после завершения обработки.');
    }

    public function show(Dataset $dataset)
    {
        Gate::authorize('update', $dataset);

        $this->loadDatasetDashboard($dataset);

        return view('datasets.show', [
            'dataset' => $dataset,
            'latestRun' => $dataset->checkRuns->first(),
        ]);
    }

    public function livePanels(Dataset $dataset): JsonResponse
    {
        Gate::authorize('update', $dataset);

        $this->loadDatasetDashboard($dataset);

        return response()->json([
            'issuesHtml' => view('datasets.partials.recent-errors-table', ['dataset' => $dataset])->render(),
            'duplicatesHtml' => view('datasets.partials.recent-duplicates-table', ['dataset' => $dataset])->render(),
            'statsHtml' => view('datasets.partials.dataset-status-card', [
                'dataset' => $dataset,
                'latestRun' => $dataset->checkRuns->first(),
            ])->render(),
        ]);
    }

    public function analyze(Dataset $dataset, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $dataset);

        $analysisService->analyze($dataset, 'manual');

        return redirect("/datasets/{$dataset->id}")
            ->with('success', 'Проверка набора запущена повторно.');
    }

    public function destroy(Dataset $dataset): RedirectResponse
    {
        Gate::authorize('update', $dataset);

        $dataset->delete();

        return redirect('/datasets', 303)
            ->with('success', 'Набор данных удален.');
    }

    private function loadDatasetDashboard(Dataset $dataset): void
    {
        $dataset->load([
            'checkRuns',
            'issues' => fn ($query) => $query->latest()->limit(8),
            'duplicateCandidates' => fn ($query) => $query->with(['primaryRow', 'duplicateRow'])->latest()->limit(8),
        ]);
    }
}
