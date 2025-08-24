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
        $errorMessage = $request->input('error_message');
        $errorFile = $request->input('error_file');
        $errorLine = $request->input('error_line');

        $apiKey = config('ai-error-handler.perplexity_api_key');
        $model = config('ai-error-handler.model', 'sonar');
        
        // Map simple model names to full Perplexity model names
        // $modelMap = [
        //     'sonar' => 'llama-3.1-sonar-large-128k-online',
        //     'sonar-small' => 'llama-3.1-sonar-small-128k-online',
        //     'sonar-huge' => 'llama-3.1-sonar-huge-128k-online',
        // ];
        
        $fullModelName = $model;

        $response = Http::withToken($apiKey)->post('https://api.perplexity.ai/chat/completions', [
            'model' => $fullModelName,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an AI Laravel debugging assistant.'],
                ['role' => 'user', 'content' => "Fix this error: $errorMessage in file $errorFile on line $errorLine"]
            ]
        ]);

        $aiFix = $response->json()['choices'][0]['message']['content'] ?? 'No fix suggestion available.';
        
        // Extract code fixes from AI response
        $extractedFixes = $this->fixParser->extractCodeFromAIResponse($aiFix);
        
        // Check if backup exists for this file
        $hasBackup = $this->backupService->hasBackup($errorFile);
        $backupFileName = $hasBackup;

        return view('ai-error-handler::fix-result', [
            'aiFix' => $aiFix,
            'errorFile' => $errorFile,
            'errorLine' => $errorLine,
            'extractedFixes' => $extractedFixes,
            'hasBackup' => $hasBackup,
            'backupFileName' => $backupFileName,
        ]);
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
                return response()->json(['success' => false, 'message' => 'File not found.']);
            }

            if (!File::isWritable($errorFile)) {
                return response()->json(['success' => false, 'message' => 'File is not writable.']);
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
                return response()->json(['success' => false, 'message' => 'Failed to apply fix.']);
            }

            return response()->json([
                'success' => true, 
                'message' => 'Fix applied successfully!',
                'backup_created' => $backupFileName
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
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
                
                return response()->json([
                    'success' => true, 
                    'message' => 'File restored successfully from backup!'
                ]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to restore file.']);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
