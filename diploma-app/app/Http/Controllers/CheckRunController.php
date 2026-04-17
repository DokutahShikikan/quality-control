<?php

namespace App\Http\Controllers;

use App\Models\CheckRun;
use Illuminate\Support\Facades\Auth;

class CheckRunController extends Controller
{
    public function index()
    {
        $runs = CheckRun::query()
            ->whereIn('dataset_id', Auth::user()->datasets()->pluck('id'))
            ->with('dataset')
            ->latest()
            ->paginate(40)
            ->withQueryString();

        return view('checks.index', ['runs' => $runs]);
    }
}
