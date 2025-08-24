<?php

use Illuminate\Support\Facades\Route;
use LaravelAIErrorHandler\AIErrorFixController;

Route::post('/ai-error-handler/fix', [AIErrorFixController::class, 'fix']);
