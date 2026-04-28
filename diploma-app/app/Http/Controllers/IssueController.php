<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\DatasetRow;
use App\Models\Issue;
use App\Services\DatasetAnalysisService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class       IssueController extends Controller
{
    public function index(Request $request)
    {
        return view('issues.index', $this->issuesViewData($request));
    }

    public function table(Request $request)
    {
        return view('issues.partials.table-panel', $this->issuesViewData($request));
    }

    public function fix(Request $request, int $issue): RedirectResponse|JsonResponse
    {
        $issue = $this->resolveIssue($issue);

        if (! $issue) {
            return $this->fixFromSnapshot($request);
        }

        Gate::authorize('update', $issue->dataset);

        if (! $issue->suggested_value || ! $issue->datasetRow || ! $issue->column_name) {
            return $this->actionResponse($request, 'Для этой ошибки нет безопасного автоматического исправления по шаблону.', false, 422);
        }

        $payload = $issue->datasetRow->payload;
        $payload[$issue->column_name] = $issue->suggested_value;

        DB::transaction(function () use ($issue, $payload) {
            $issue->datasetRow->update(['payload' => $payload]);
            $this->similarIssuesQuery($issue)->update(['status' => 'fixed']);
        });

        $this->refreshDatasetState($issue->dataset);

        return $this->actionResponse($request, 'Значение исправлено.');
    }

    public function ignore(Request $request, int $issue): RedirectResponse|JsonResponse
    {
        $issue = $this->resolveIssue($issue);

        if (! $issue) {
            return $this->actionResponse($request, 'Эта ошибка уже обновилась после новой проверки. Открой свежий список и попробуй снова.', false, 404, true);
        }

        Gate::authorize('update', $issue->dataset);

        $issue->update(['status' => 'ignored']);
        $this->refreshDatasetState($issue->dataset);

        return $this->actionResponse($request, 'Ошибка отмечена как пропущенная.');
    }

    public function fixSimilar(Request $request, int $issue): RedirectResponse|JsonResponse
    {
        $issue = $this->resolveIssue($issue);

        if (! $issue) {
            return $this->actionResponse($request, 'Эта ошибка уже обновилась после новой проверки. Открой свежий список и попробуй снова.', false, 404, true);
        }

        Gate::authorize('update', $issue->dataset);

        if (! $issue->suggested_value || ! $issue->column_name) {
            return $this->actionResponse($request, 'Для этой ошибки нет безопасного автоматического исправления по шаблону.', false, 422);
        }

        $similarIssues = $this->similarIssuesQuery($issue)
            ->with('datasetRow')
            ->get();

        if ($similarIssues->isEmpty()) {
            return $this->actionResponse($request, 'Похожих открытых ошибок для массового исправления не найдено.', false, 404);
        }

        DB::transaction(function () use ($similarIssues, $issue) {
            foreach ($similarIssues as $similarIssue) {
                if (! $similarIssue->datasetRow) {
                    continue;
                }

                $payload = $similarIssue->datasetRow->payload;
                $payload[$issue->column_name] = $issue->suggested_value;

                $similarIssue->datasetRow->update(['payload' => $payload]);
                $similarIssue->update(['status' => 'fixed']);
            }
        });

        $this->refreshDatasetState($issue->dataset);

        return $this->actionResponse($request, 'Все подобные ошибки исправлены.');
    }

    private function resolveIssue(int $issueId): ?Issue
    {
        return Issue::query()
            ->whereKey($issueId)
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'datasetRow'])
            ->first();
    }

    private function fixFromSnapshot(Request $request): RedirectResponse|JsonResponse
    {
        $datasetId = (int) $request->integer('dataset_id');
        $datasetRowId = (int) $request->integer('dataset_row_id');
        $columnName = trim((string) $request->input('column_name'));
        $suggestedValue = (string) $request->input('suggested_value', '');

        if (! $datasetId || ! $datasetRowId || $columnName === '' || $suggestedValue === '') {
            return $this->actionResponse($request, 'Эта ошибка уже обновилась после новой проверки. Открой свежий список и попробуй снова.', false, 404, true);
        }

        $dataset = Dataset::query()
            ->whereKey($datasetId)
            ->where('user_id', Auth::id())
            ->first();

        if (! $dataset) {
            return $this->actionResponse($request, 'Таблица для этого исправления не найдена.', false, 404, true);
        }

        Gate::authorize('update', $dataset);

        $datasetRow = DatasetRow::query()
            ->whereKey($datasetRowId)
            ->where('dataset_id', $dataset->id)
            ->first();

        if (! $datasetRow) {
            return $this->actionResponse($request, 'Строка уже изменилась после новой проверки. Открой свежий список и попробуй снова.', false, 404, true);
        }

        $payload = $datasetRow->payload;
        $payload[$columnName] = $suggestedValue;

        DB::transaction(function () use ($dataset, $datasetRow, $datasetRowId, $columnName, $suggestedValue, $payload) {
            $datasetRow->update(['payload' => $payload]);

            Issue::query()
                ->where('dataset_id', $dataset->id)
                ->where('dataset_row_id', $datasetRowId)
                ->where('column_name', $columnName)
                ->where('status', 'open')
                ->where('suggested_value', $suggestedValue)
                ->update(['status' => 'fixed']);
        });

        $this->refreshDatasetState($dataset);

        return $this->actionResponse($request, 'Значение исправлено.', true, 200, true);
    }

    private function similarIssuesQuery(Issue $issue)
    {
        return Issue::query()
            ->where('dataset_id', $issue->dataset_id)
            ->where('status', 'open')
            ->where('issue_type', $issue->issue_type)
            ->where('column_name', $issue->column_name)
            ->where('suggested_value', $issue->suggested_value)
            ->where(function ($query) use ($issue) {
                if ($issue->original_value === null) {
                    $query->whereNull('original_value');

                    return;
                }

                $query->where('original_value', $issue->original_value);
            });
    }

    private function issuesViewData(Request $request): array
    {
        $statusLabels = [
            'open' => 'Открыта',
            'fixed' => 'Исправлена',
            'ignored' => 'Пропущена',
        ];

        $issueTypeLabels = [
            'missing_value' => 'Пустое значение',
            'invalid_format' => 'Неверный формат',
            'out_of_range' => 'Недопустимое значение',
            'duplicate_value' => 'Повтор значения',
        ];

        $severityLabels = [
            'high' => 'Высокая',
            'medium' => 'Средняя',
            'low' => 'Низкая',
        ];

        $selectedDataset = null;
        $datasetId = (int) $request->integer('dataset');

        $issueQuery = Issue::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with(['dataset', 'datasetRow'])
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

        return [
            'issues' => $issueQuery->paginate(50)->withQueryString(),
            'filters' => $request->only(['q', 'status', 'issue_type', 'severity', 'sort', 'dataset']),
            'statusLabels' => $statusLabels,
            'issueTypeLabels' => $issueTypeLabels,
            'severityLabels' => $severityLabels,
            'selectedDataset' => $selectedDataset,
        ];
    }

    private function actionResponse(
        Request $request,
        string $message,
        bool $success = true,
        int $status = 200,
        bool $redirectToIssues = false,
    ): RedirectResponse|JsonResponse {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'status' => $success ? 'success' : 'error',
                'message' => $message,
            ], $status);
        }

        $redirectUrl = '/issues';

        if ($request->filled('dataset')) {
            $redirectUrl .= '?dataset='.$request->integer('dataset');
        }

        $response = $redirectToIssues ? redirect($redirectUrl) : back();

        return $success
            ? $response->with('success', $message)
            : $response->with('error', $message);
    }

    private function refreshDatasetState(Dataset $dataset): void
    {
        app(DatasetAnalysisService::class)->refreshDatasetSummary($dataset);
    }
}
