<?php

namespace App\Http\Controllers;

use App\Models\QualityRule;

class QualityRuleController extends Controller
{
    public function index()
    {
        return view('rules.index', [
            'rules' => QualityRule::query()->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
