<?php

namespace LaravelAIErrorHandler\Services;

class AIFixParser
{
    public function extractCodeFromAIResponse(string $aiResponse): array
    {
        $fixes = [];
        
        // Extract code blocks from markdown-style code blocks
        preg_match_all('/```(?:php|laravel)?\s*\n(.*?)\n```/s', $aiResponse, $codeBlocks);
        
        if (!empty($codeBlocks[1])) {
            foreach ($codeBlocks[1] as $index => $code) {
                $fixes[] = [
                    'type' => 'code_block',
                    'content' => trim($code),
                    'index' => $index + 1
                ];
            }
        }
        
        // Also extract single-line code snippets
        preg_match_all('/`([^`\n]+)`/', $aiResponse, $inlineCode);
        if (!empty($inlineCode[1])) {
            foreach ($inlineCode[1] as $index => $code) {
                // Skip if it's just a variable or short snippet
                if (strlen($code) > 20 && (strpos($code, '->') !== false || strpos($code, '::') !== false)) {
                    $fixes[] = [
                        'type' => 'inline_code',
                        'content' => trim($code),
                        'index' => count($fixes) + 1
                    ];
                }
            }
        }
        
        return $fixes;
    }
    
    public function generateFixedContent(string $originalContent, string $fixCode, int $errorLine): string
    {
        $lines = explode("\n", $originalContent);
        $fixLines = explode("\n", $fixCode);
        
        // Try to intelligently apply the fix
        // If the fix contains function/class definitions, replace the entire function
        if (preg_match('/^\s*(public|private|protected|function|class|interface)/', $fixCode)) {
            return $this->replaceFunction($lines, $fixLines, $errorLine);
        }
        
        // For simple fixes, replace around the error line
        return $this->replaceAroundErrorLine($lines, $fixLines, $errorLine);
    }
    
    private function replaceFunction(array $originalLines, array $fixLines, int $errorLine): string
    {
        $startLine = $this->findFunctionStart($originalLines, $errorLine);
        $endLine = $this->findFunctionEnd($originalLines, $startLine);
        
        // Replace the function with the new code
        $newLines = array_merge(
            array_slice($originalLines, 0, $startLine),
            $fixLines,
            array_slice($originalLines, $endLine + 1)
        );
        
        return implode("\n", $newLines);
    }
    
    private function replaceAroundErrorLine(array $originalLines, array $fixLines, int $errorLine): string
    {
        $lineIndex = $errorLine - 1; // Convert to 0-based index
        
        // Replace the error line and surrounding context
        $contextLines = min(count($fixLines), 5);
        $startReplace = max(0, $lineIndex - floor($contextLines / 2));
        $endReplace = min(count($originalLines) - 1, $lineIndex + floor($contextLines / 2));
        
        $newLines = array_merge(
            array_slice($originalLines, 0, $startReplace),
            $fixLines,
            array_slice($originalLines, $endReplace + 1)
        );
        
        return implode("\n", $newLines);
    }
    
    private function findFunctionStart(array $lines, int $errorLine): int
    {
        for ($i = $errorLine - 1; $i >= 0; $i--) {
            if (preg_match('/^\s*(public|private|protected|function)/', $lines[$i])) {
                return $i;
            }
        }
        return max(0, $errorLine - 5);
    }
    
    private function findFunctionEnd(array $lines, int $startLine): int
    {
        $braceCount = 0;
        $foundOpenBrace = false;
        
        for ($i = $startLine; $i < count($lines); $i++) {
            $braceCount += substr_count($lines[$i], '{');
            $braceCount -= substr_count($lines[$i], '}');
            
            if ($braceCount > 0) {
                $foundOpenBrace = true;
            }
            
            if ($foundOpenBrace && $braceCount === 0) {
                return $i;
            }
        }
        
        return min(count($lines) - 1, $startLine + 20);
    }
}
