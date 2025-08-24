<?php

namespace LaravelAIErrorHandler\Services;

class AIFixParser
{
    public function extractCodeFromAIResponse(string $aiResponse): array
    {
        $fixes = [];
        
        // Extract code blocks from markdown-style code blocks (PHP only)
        preg_match_all('/```(?:php|laravel)?\s*\n(.*?)\n```/s', $aiResponse, $codeBlocks);
        
        if (!empty($codeBlocks[1])) {
            foreach ($codeBlocks[1] as $index => $code) {
                $cleanCode = $this->cleanCodeContent($code);
                if ($this->isValidPHPCode($cleanCode)) {
                    $fixes[] = [
                        'type' => 'code_block',
                        'content' => $cleanCode,
                        'index' => $index + 1
                    ];
                }
            }
        }
        
        // Extract PHP code that might not be in code blocks but looks like valid PHP
        preg_match_all('/(?:^|\n)([a-zA-Z_$][a-zA-Z0-9_$]*\s*[=->:;(){}[\]]+.*?)(?:\n|$)/s', $aiResponse, $phpLines);
        if (!empty($phpLines[1])) {
            foreach ($phpLines[1] as $index => $code) {
                $cleanCode = $this->cleanCodeContent($code);
                if ($this->isValidPHPCode($cleanCode) && strlen($cleanCode) > 10) {
                    $fixes[] = [
                        'type' => 'php_line',
                        'content' => $cleanCode,
                        'index' => count($fixes) + 1
                    ];
                }
            }
        }
        
        return $fixes;
    }
    
    private function cleanCodeContent(string $code): string
    {
        // Remove common non-PHP text patterns
        $code = preg_replace('/^(Here is|Here\'s|Try this|Solution|Fix|Code|Example):\s*/i', '', $code);
        $code = preg_replace('/\s*(This will|This should|This code|The fix|The solution).*$/i', '', $code);
        $code = preg_replace('/\s*\/\/\s*[^\/\n]*$/m', '', $code); // Remove trailing comments
        $code = preg_replace('/^\s*\/\/\s*[^\/\n]*\n/m', '', $code); // Remove comment-only lines
        
        return trim($code);
    }
    
    private function isValidPHPCode(string $code): bool
    {
        // Basic PHP syntax validation
        $code = trim($code);
        
        // Must contain PHP syntax elements
        if (empty($code)) return false;
        
        // Check for common PHP patterns
        $phpPatterns = [
            '/\$[a-zA-Z_][a-zA-Z0-9_]*/',           // Variables
            '/function\s+[a-zA-Z_][a-zA-Z0-9_]*/',  // Functions
            '/class\s+[a-zA-Z_][a-zA-Z0-9_]*/',     // Classes
            '/[a-zA-Z_][a-zA-Z0-9_]*\s*->\s*[a-zA-Z_][a-zA-Z0-9_]*/', // Object methods
            '/[a-zA-Z_][a-zA-Z0-9_]*\s*::\s*[a-zA-Z_][a-zA-Z0-9_]*/', // Static methods
            '/[a-zA-Z_][a-zA-Z0-9_]*\s*\(/',        // Function calls
            '/\$\w+\s*=\s*[^;]+;/',                 // Variable assignment
            '/if\s*\(/',                             // If statements
            '/foreach\s*\(/',                        // Foreach loops
            '/return\s+/',                           // Return statements
        ];
        
        foreach ($phpPatterns as $pattern) {
            if (preg_match($pattern, $code)) {
                return true;
            }
        }
        
        return false;
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
