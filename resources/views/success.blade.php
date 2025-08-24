<!DOCTYPE html>
<html>
<head>
    <title>AI Fix Applied Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .success-header { text-align: center; margin-bottom: 30px; }
        .success-icon { font-size: 48px; color: #28a745; margin-bottom: 20px; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        .info-box { background: #e7f3ff; padding: 20px; border-radius: 4px; margin: 20px 0; border-left: 4px solid #007bff; }
        .backup-info { background: #f8f9fa; padding: 20px; border-radius: 4px; margin: 20px 0; border: 1px solid #dee2e6; }
        .file-details { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 15px 0; }
        .file-details strong { color: #495057; }
        .backup-url { background: #e9ecef; padding: 10px; border-radius: 4px; font-family: monospace; word-break: break-all; margin: 10px 0; }
        button { background: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; margin-right: 10px; }
        button:hover { background: #0056b3; }
        button.danger { background: #dc3545; }
        button.danger:hover { background: #c82333; }
        button.warning { background: #ffc107; color: #212529; }
        button.warning:hover { background: #e0a800; }
        .actions { margin-top: 30px; text-align: center; }
        .back-link { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-header">
            <div class="success-icon">✅</div>
            <h1>AI Fix Applied Successfully!</h1>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        <div class="info-box">
            <h3>What Happened:</h3>
            <p>Your AI-suggested fix has been successfully applied to the file. A backup was automatically created before making the changes to ensure your data is safe.</p>
        </div>

        <div class="file-details">
            <h3>File Details:</h3>
            <p><strong>File:</strong> {{ $errorFile }}</p>
            <p><strong>Line:</strong> {{ $errorLine }}</p>
            <p><strong>Backup Created:</strong> {{ $backupFile }}</p>
        </div>

        @if($hasBackup)
            <div class="backup-info">
                <h3>Backup Information:</h3>
                <p><strong>Backup File:</strong> {{ $backupFile }}</p>
                <p><strong>Backup Location:</strong></p>
                <div class="backup-url">storage/app/ai-error-handler/backups/{{ $backupFile }}</div>
                <p><em>This backup contains the original version of your file before the AI fix was applied.</em></p>
            </div>

            <div class="actions">
                <form method="POST" action="/ai-error-handler/restore" style="display: inline;">
                    @csrf
                    <input type="hidden" name="error_file" value="{{ $errorFile }}">
                    <input type="hidden" name="backup_file" value="{{ $backupFile }}">
                    <button type="submit" class="warning" onclick="return confirm('Are you sure you want to restore the file from backup? This will overwrite the current changes.')">
                        🔄 Restore from Backup
                    </button>
                </form>
                
                <a href="/ai-error-handler/fix" class="back-link">
                    <button type="button">🆕 Fix Another Error</button>
                </a>
            </div>
        @else
            <div class="alert alert-warning">
                <strong>Note:</strong> Backup file not found. It may have been deleted or moved.
            </div>
            
            <div class="actions">
                <a href="/ai-error-handler/fix" class="back-link">
                    <button type="button">🆕 Fix Another Error</button>
                </a>
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url()->previous() }}" class="back-link">← Go Back</a>
        </div>
    </div>
</body>
</html>
