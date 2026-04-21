<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Services\DeepSeekCorrectionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use RuntimeException;

class AutoFixController extends Controller
{
    public function index()
    {
        $datasets = Auth::user()->datasets()->latest()->get();

        return view('autofix.index', [
            'datasets' => $datasets,
            'isDeepSeekConfigured' => app(DeepSeekCorrectionService::class)->isConfigured(),
        ]);
    }

    public function run(Dataset $dataset, DeepSeekCorrectionService $deepSeekCorrectionService): RedirectResponse
    {
        Gate::authorize('update', $dataset);

        try {
            $result = $deepSeekCorrectionService->correctDataset($dataset);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with(
            'success',
            "ИИ обработал таблицу \"{$dataset->name}\": проверено {$result['checked']}, исправлено {$result['fixed']}, пропущено {$result['skipped']}, ошибок запроса {$result['failed']}."
        );
    }
}
