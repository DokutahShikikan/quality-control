<?php

namespace App\Http\Controllers;

use App\Models\CheckRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRunController extends Controller
{
    public function index(Request $request)
    {
        $runQuery = CheckRun::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with('dataset')
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = trim((string) $request->string('q'));
                $query->where(function ($inner) use ($term) {
                    $inner->where('trigger_source', 'like', "%{$term}%")
                        ->orWhere('status', 'like', "%{$term}%")
                        ->orWhereHas('dataset', fn ($datasetQuery) => $datasetQuery->where('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->value()))
            ->when($request->filled('trigger_source'), fn ($query) => $query->where('trigger_source', $request->string('trigger_source')->value()));

        match ($request->string('sort')->value()) {
            'oldest' => $runQuery->oldest(),
            'issues' => $runQuery->orderByDesc('issues_count')->latest(),
            'duplicates' => $runQuery->orderByDesc('duplicate_pairs_count')->latest(),
            default => $runQuery->latest(),
        };

        $runs = $runQuery->paginate(40)->withQueryString();

        return view('checks.index', [
            'runs' => $runs,
            'filters' => $request->only(['q', 'status', 'trigger_source', 'sort']),
        ]);
    }
}
