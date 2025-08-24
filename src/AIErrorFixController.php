<?php

namespace LaravelAIErrorHandler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AIErrorFixController
{
    public function fix(Request $request)
    {
        $errorMessage = $request->input('error_message');
        $errorFile = $request->input('error_file');
        $errorLine = $request->input('error_line');

        $apiKey = config('ai-error-handler.perplexity_api_key');

        $response = Http::withToken($apiKey)->post('https://api.perplexity.ai/chat/completions', [
            'model' => 'llama-3.1-sonar-large-128k-online',
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI Laravel debugging assistant.'],
                ['role' => 'user', 'content' => "Fix this error: $errorMessage in file $errorFile on line $errorLine"]
            ]
        ]);

        $aiFix = $response->json()['choices'][0]['message']['content'] ?? 'No fix suggestion available.';

        return view('ai-error-handler::fix-result', [
            'aiFix' => $aiFix,
            'errorFile' => $errorFile,
            'errorLine' => $errorLine,
        ]);
    }
}
