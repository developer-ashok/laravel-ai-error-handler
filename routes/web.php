<?php

use Illuminate\Support\Facades\Route;
use LaravelAIErrorHandler\AIErrorFixController;

// Main fix route - handles both GET and POST
Route::match(['GET', 'POST'], '/ai-error-handler/fix', [AIErrorFixController::class, 'fix']);
Route::post('/ai-error-handler/apply-fix', [AIErrorFixController::class, 'applyFix']);
Route::post('/ai-error-handler/restore', [AIErrorFixController::class, 'restore']);
