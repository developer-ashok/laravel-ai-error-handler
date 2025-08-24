<?php

use Illuminate\Support\Facades\Route;
use LaravelAIErrorHandler\AIErrorFixController;

Route::post('/ai-error-handler/fix', [AIErrorFixController::class, 'fix']);
Route::post('/ai-error-handler/apply-fix', [AIErrorFixController::class, 'applyFix']);
Route::post('/ai-error-handler/restore', [AIErrorFixController::class, 'restore']);
