<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class AutoFixController extends Controller
{
    public function index()
    {
        $datasets = Auth::user()->datasets()->latest()->get();

        return view('autofix.index', ['datasets' => $datasets]);
    }
}
