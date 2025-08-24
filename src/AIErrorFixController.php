<?php

namespace LaravelAIErrorHandler;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use LaravelAIErrorHandler\Services\BackupService;
use LaravelAIErrorHandler\Services\AIFixParser;

class AIErrorFixController
{
    protected BackupService $backupService;
    protected AIFixParser $fixParser;

    public function __construct(BackupService $backupService, AIFixParser $fixParser)
    {
        $this->backupService = $backupService;
        $this->fixParser = $fixParser;
    }

    public function fix(Request $request)
    {
        // Handle GET requests (direct access)
        if ($request->isMethod('GET')) {
            return view('ai-error-handler::error-form', [
                'title' => 'AI Error Handler - Manual Fix Request',
                'message' => 'Please provide error details to get AI-powered fixes.'
            ]);
        }

        // Handle POST requests (form submission)
        $errorMessage = $request->input('error_message');
        $errorFile = $request->input('error_file');
        $errorLine = $request->input('error_line');

        // Validate required inputs
        if (empty($errorMessage) || empty($errorFile) || empty($errorLine)) {
            return redirect()->back()->with('error', 'All error details are required: message, file, and line number.');
        }

        $apiKey = config('ai-error-handler.perplexity_api_key');
        $model = config('ai-error-handler.model', 'sonar');

        try {
            // First API call: Get detailed explanation and analysis
            $explanationResponse = Http::withToken($apiKey)->post('https://api.perplexity.ai/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a PHP debugging expert. Analyze the error and provide a detailed explanation of what went wrong, why it happened, and the best approach to fix it. Include context about the error type, common causes, and prevention tips.'],
                    ['role' => 'user', 'content' => "Analyze this PHP error: $errorMessage in file $errorFile on line $errorLine. Provide a detailed explanation of the problem and the best approach to fix it."]
                ]
            ]);

            $explanation = $explanationResponse->json()['choices'][0]['message']['content'] ?? 'No explanation available.';

            // Second API call: Get clean, executable code fixes only
            $codeResponse = Http::withToken($apiKey)->post('https://api.perplexity.ai/chat/completions', [
                'model' => $model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a PHP code fixer. Your ONLY job is to provide executable PHP code that fixes the given error. IMPORTANT RULES: 1) Provide ONLY valid PHP code - no explanations, comments, or text outside code blocks. 2) If you provide multiple solutions, wrap each in separate ```php code blocks. 3) The code must be ready to copy-paste and run. 4) Do not include markdown formatting, explanations, or any non-PHP text. 5) Focus on the specific error location and provide minimal, targeted fixes.'],
                    ['role' => 'user', 'content' => "Fix this PHP error: $errorMessage in file $errorFile on line $errorLine. Provide ONLY executable PHP code that fixes the issue."]
                ]
            ]);

            $aiFix = $codeResponse->json()['choices'][0]['message']['content'] ?? 'No fix suggestion available.';
            
            // Extract code fixes from AI response
            $extractedFixes = $this->fixParser->extractCodeFromAIResponse($aiFix);
            
            // Check if backup exists for this file
            $hasBackup = $this->backupService->hasBackup($errorFile);
            $backupFileName = $hasBackup;

            return view('ai-error-handler::fix-result', [
                'explanation' => $explanation,
                'aiFix' => $aiFix,
                'errorFile' => $errorFile,
                'errorLine' => $errorLine,
                'extractedFixes' => $extractedFixes,
                'hasBackup' => $hasBackup,
                'backupFileName' => $backupFileName,
            ]);

        } catch (\Exception $e) {
            \Log::error('Error calling Perplexity AI: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to get AI fix: ' . $e->getMessage());
        }
    }

    public function applyFix(Request $request)
    {
        $request->validate([
            'error_file' => 'required|string',
            'error_line' => 'required|integer',
            'fix_code' => 'required|string',
            'fix_index' => 'required|integer'
        ]);

        $errorFile = $request->input('error_file');
        $errorLine = (int) $request->input('error_line');
        $fixCode = $request->input('fix_code');
        $fixIndex = (int) $request->input('fix_index');
        
        // Log the request for debugging
        \Log::info('Applying AI fix', [
            'error_file' => $errorFile,
            'error_line' => $errorLine,
            'fix_code' => $fixCode,
            'fix_index' => $fixIndex
        ]);

        try {
            // Validate file exists and is writable
            if (!File::exists($errorFile)) {
                return redirect()->back()->with('error', 'File not found: ' . $errorFile);
            }

            if (!File::isWritable($errorFile)) {
                return redirect()->back()->with('error', 'File is not writable: ' . $errorFile);
            }

            // Create backup before applying fix
            $backupFileName = $this->backupService->createBackup($errorFile);

            // Read original content
            $originalContent = File::get($errorFile);

            // Generate fixed content
            $fixedContent = $this->fixParser->generateFixedContent($originalContent, $fixCode, $errorLine);

            // Apply the fix
            if (File::put($errorFile, $fixedContent) === false) {
                // Restore from backup if fix failed
                $this->backupService->restoreFromBackup($backupFileName, $errorFile);
                return redirect()->back()->with('error', 'Failed to apply fix to file.');
            }

            return redirect()->route('ai-error-handler.success')->with([
                'success' => 'Fix applied successfully!',
                'backup_file' => $backupFileName,
                'error_file' => $errorFile,
                'error_line' => $errorLine
            ]);

        } catch (\Exception $e) {
            \Log::error('Error applying AI fix: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error applying fix: ' . $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $request->validate([
            'error_file' => 'required|string',
            'backup_file' => 'required|string'
        ]);

        $errorFile = $request->input('error_file');
        $backupFile = $request->input('backup_file');

        try {
            $restored = $this->backupService->restoreFromBackup($backupFile, $errorFile);
            
            if ($restored) {
                // Delete the backup after successful restore
                $this->backupService->deleteBackup($backupFile);
                
                return redirect()->route('ai-error-handler.success')->with([
                    'success' => 'File restored successfully from backup!',
                    'backup_file' => 'Backup deleted after restoration',
                    'error_file' => $errorFile,
                    'error_line' => 0
                ]);
            } else {
                return redirect()->back()->with('error', 'Failed to restore file from backup.');
            }

        } catch (\Exception $e) {
            \Log::error('Error restoring from backup: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring file: ' . $e->getMessage());
        }
    }

    public function showSuccess(Request $request)
    {
        if (!session('success')) {
            return redirect()->route('ai-error-handler.fix');
        }

        $backupFile = session('backup_file');
        $errorFile = session('error_file');
        $errorLine = session('error_line');

        // Check if backup still exists
        $hasBackup = $this->backupService->hasBackup($errorFile);

        return view('ai-error-handler::success', [
            'success' => session('success'),
            'backupFile' => $backupFile,
            'errorFile' => $errorFile,
            'errorLine' => $errorLine,
            'hasBackup' => $hasBackup
        ]);
    }
}
