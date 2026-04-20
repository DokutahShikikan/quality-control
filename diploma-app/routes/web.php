<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SessionsController;
use App\Http\Controllers\AutoFixController;
use App\Http\Controllers\CheckRunController;
use App\Http\Controllers\DatasetController;
use App\Http\Controllers\DuplicateCandidateController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\QualityRuleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/datasets');
});

Route::middleware('auth')->group(function () {
    Route::get('/datasets', [DatasetController::class, 'index']);
    Route::get('/datasets/create', [DatasetController::class, 'create']);
    Route::post('/datasets', [DatasetController::class, 'store']);
    Route::get('/datasets/{dataset}', [DatasetController::class, 'show']);
    Route::get('/datasets/{dataset}/live-panels', [DatasetController::class, 'livePanels']);
    Route::post('/datasets/{dataset}/analyze', [DatasetController::class, 'analyze']);
    Route::delete('/datasets/{dataset}', [DatasetController::class, 'destroy']);

    Route::get('/rules', [QualityRuleController::class, 'index']);
    Route::get('/checks', [CheckRunController::class, 'index']);
    Route::get('/issues', [IssueController::class, 'index']);
    Route::post('/issues/{issue}/fix', [IssueController::class, 'fix']);
    Route::post('/issues/{issue}/ignore', [IssueController::class, 'ignore']);
    Route::get('/duplicates', [DuplicateCandidateController::class, 'index']);
    Route::post('/duplicates/{duplicateCandidate}/fix', [DuplicateCandidateController::class, 'fix']);
    Route::post('/duplicates/{duplicateCandidate}/ignore', [DuplicateCandidateController::class, 'ignore']);
    Route::get('/autofix', [AutoFixController::class, 'index']);

    Route::delete('/logout', [SessionsController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create'])->name('login');
    Route::post('/login', [SessionsController::class, 'store']);
});
