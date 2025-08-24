<?php

namespace LaravelAIErrorHandler\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    private string $backupDisk = 'local';
    private string $backupPath = 'ai-error-handler/backups';

    public function createBackup(string $filePath): string
    {
        if (!File::exists($filePath)) {
            throw new \Exception("File not found: {$filePath}");
        }

        $content = File::get($filePath);
        $backupFileName = $this->generateBackupFileName($filePath);
        $backupFullPath = $this->backupPath . '/' . $backupFileName;

        Storage::disk($this->backupDisk)->put($backupFullPath, $content);

        return $backupFileName;
    }

    public function restoreFromBackup(string $backupFileName, string $originalFilePath): bool
    {
        $backupFullPath = $this->backupPath . '/' . $backupFileName;

        if (!Storage::disk($this->backupDisk)->exists($backupFullPath)) {
            throw new \Exception("Backup file not found: {$backupFileName}");
        }

        $backupContent = Storage::disk($this->backupDisk)->get($backupFullPath);
        
        return File::put($originalFilePath, $backupContent) !== false;
    }

    public function hasBackup(string $filePath): ?string
    {
        $backupPattern = $this->generateBackupPattern($filePath);
        $backupFiles = Storage::disk($this->backupDisk)->files($this->backupPath);
        
        foreach ($backupFiles as $file) {
            $fileName = basename($file);
            if (preg_match($backupPattern, $fileName)) {
                return $fileName;
            }
        }

        return null;
    }

    public function deleteBackup(string $backupFileName): bool
    {
        $backupFullPath = $this->backupPath . '/' . $backupFileName;
        
        if (Storage::disk($this->backupDisk)->exists($backupFullPath)) {
            return Storage::disk($this->backupDisk)->delete($backupFullPath);
        }

        return false;
    }

    private function generateBackupFileName(string $filePath): string
    {
        $fileName = basename($filePath);
        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $timestamp = date('Y-m-d_H-i-s');
        $hash = substr(md5($filePath), 0, 8);

        return "{$fileNameWithoutExt}_{$timestamp}_{$hash}.{$extension}";
    }

    private function generateBackupPattern(string $filePath): string
    {
        $fileName = basename($filePath);
        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $hash = substr(md5($filePath), 0, 8);

        return "/^{$fileNameWithoutExt}_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}-\d{2}_{$hash}\.{$extension}$/";
    }
}
