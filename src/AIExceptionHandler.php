<?php

namespace LaravelAIErrorHandler;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Http;

class AIExceptionHandler extends ExceptionHandler
{
    public function render($request, Throwable $e)
    {
        if (!config('ai-error-handler.enabled')) {
            return parent::render($request, $e);
        }

        $errorMessage = $e->getMessage();
        $errorFile = $e->getFile();
        $errorLine = $e->getLine();

        return response()->view('ai-error-handler::error', [
            'errorMessage' => $errorMessage,
            'errorFile' => $errorFile,
            'errorLine' => $errorLine,
        ], 500);
    }
}
