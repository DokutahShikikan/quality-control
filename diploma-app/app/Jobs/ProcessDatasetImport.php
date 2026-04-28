<?php

namespace App\Jobs;

use App\Models\Dataset;
use App\Services\DatasetAnalysisService;
use App\Services\SpreadsheetImportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProcessDatasetImport implements ShouldQueue
{
    use Queueable;

    public function __construct(public int $datasetId)
    {
    }

    public function handle(
        SpreadsheetImportService $importService,
        DatasetAnalysisService $analysisService,
    ): void {
        @ini_set('memory_limit', '512M');
        @set_time_limit(0);

        $dataset = Dataset::query()->find($this->datasetId);

        if (! $dataset || ! $dataset->source_path) {
            return;
        }

        $dataset->update([
            'import_status' => 'processing',
            'import_error' => null,
        ]);

        try {
            $absolutePath = Storage::disk('local')->path($dataset->source_path);
            $import = $importService->importStoredFile($absolutePath, $dataset->source_filename);

            DB::transaction(function () use ($dataset, $import) {
                $dataset->rows()->delete();

                $dataset->update([
                    'headers' => $import['headers'],
                    'total_rows' => count($import['rows']),
                    'total_columns' => count($import['headers']),
                    'import_status' => 'ready',
                    'import_error' => null,
                ]);

                $timestamp = now();

                foreach (array_chunk($import['rows'], 500) as $chunk) {
                    $records = array_map(fn (array $row) => [
                        'dataset_id' => $dataset->id,
                        'row_index' => $row['row_index'],
                        'payload' => json_encode($row['payload'], JSON_UNESCAPED_UNICODE),
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ], $chunk);

                    DB::table('dataset_rows')->insert($records);
                }
            });

            $analysisService->analyze($dataset->fresh(), 'import');

            if ($dataset->source_path && Storage::disk('local')->exists($dataset->source_path)) {
                Storage::disk('local')->delete($dataset->source_path);
            }

            $dataset->update(['source_path' => null]);
        } catch (Throwable $exception) {
            $dataset->update([
                'import_status' => 'failed',
                'import_error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }
}
