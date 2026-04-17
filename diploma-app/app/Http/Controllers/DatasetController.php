<?php

namespace App\Http\Controllers;

use App\Http\Requests\DatasetRequest;
use App\Models\Dataset;
use App\Services\DatasetAnalysisService;
use App\Services\SpreadsheetImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class DatasetController extends Controller
{
    public function index()
    {
        $datasets = Auth::user()->datasets()->latest()->get();
        $metrics = [
            'datasets' => $datasets->count(),
            'open_issues' => $datasets->sum(fn (Dataset $dataset) => data_get($dataset->metrics, 'open_issues', 0)),
            'open_duplicates' => $datasets->sum(fn (Dataset $dataset) => data_get($dataset->metrics, 'open_duplicates', 0)),
            'ready_for_ai' => $datasets->filter(
                fn (Dataset $dataset) => data_get($dataset->metrics, 'deepseek_stage_ready', false)
            )->count(),
        ];

        return view('datasets.index', [
            'datasets' => $datasets,
            'metrics' => $metrics,
        ]);
    }

    public function create()
    {
        return view('datasets.create');
    }

    public function store(
        DatasetRequest $request,
        SpreadsheetImportService $importService,
        DatasetAnalysisService $analysisService,
    ): RedirectResponse {
        $file = $request->file('source_file');

        try {
            $import = $importService->import($file);
        } catch (Throwable $exception) {
            return back()
                ->withInput()
                ->withErrors(['source_file' => $exception->getMessage()]);
        }

        $dataset = DB::transaction(function () use ($request, $file, $import) {
            $dataset = Auth::user()->datasets()->create([
                'name' => $request->string('name')->value(),
                'description' => $request->string('description')->value() ?: null,
                'source_filename' => $file->getClientOriginalName(),
                'source_mime' => $file->getMimeType(),
                'headers' => $import['headers'],
                'total_rows' => count($import['rows']),
                'total_columns' => count($import['headers']),
                'deepseek_enabled' => $request->boolean('deepseek_enabled'),
                'import_status' => 'ready',
            ]);

            $dataset->rows()->createMany($import['rows']);

            return $dataset;
        });

        $analysisService->analyze($dataset, 'import');

        return redirect("/datasets/{$dataset->id}")
            ->with('success', 'Набор загружен, проверен по regex-правилам и готов к разбору инцидентов.');
    }

    public function show(Dataset $dataset)
    {
        Gate::authorize('update', $dataset);

        $dataset->load([
            'checkRuns',
            'issues' => fn ($query) => $query->latest()->limit(8),
            'duplicateCandidates' => fn ($query) => $query->with(['primaryRow', 'duplicateRow'])->latest()->limit(8),
        ]);

        return view('datasets.show', [
            'dataset' => $dataset,
            'latestRun' => $dataset->checkRuns->first(),
        ]);
    }

    public function analyze(Dataset $dataset, DatasetAnalysisService $analysisService): RedirectResponse
    {
        Gate::authorize('update', $dataset);

        $analysisService->analyze($dataset, 'manual');

        return redirect("/datasets/{$dataset->id}")->with('success', 'Проверка набора запущена повторно.');
    }

    public function destroy(Dataset $dataset): RedirectResponse
    {
        Gate::authorize('update', $dataset);

        $dataset->delete();

        return redirect('/datasets')->with('success', 'Набор данных удален.');
    }
}
