<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class CheckRunController extends Controller
{
    public function index()
    {
        $runs = Auth::user()
            ->datasets()
            ->with('checkRuns')
            ->get()
            ->pluck('checkRuns')
            ->flatten()
            ->sortByDesc('created_at')
            ->values();

        return view('checks.index', ['runs' => $runs]);
    }
}
