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
        .status-badge { background: #28a745; color: white; padding: 6px 12px; border-radius: 20px; font-size: 14px; font-weight: bold; display: inline-block; }
        .line-badge { background: #007bff; color: white; padding: 4px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .file-path { background: #2d3748; color: #e2e8f0; padding: 10px 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin: 10px 0; }
        .backup-path { background: #17a2b8; color: white; padding: 10px 15px; border-radius: 6px; font-family: monospace; word-break: break-all; margin: 10px 0; }
        .updated-file-section { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); border: 2px solid #28a745; border-radius: 12px; padding: 25px; margin: 25px 0; }
        .backup-section { background: linear-gradient(135deg, #e7f3ff 0%, #cce7ff 100%); border: 2px solid #007bff; border-radius: 12px; padding: 25px; margin: 25px 0; }
        .section-header { color: #495057; font-size: 20px; font-weight: bold; margin-bottom: 15px; display: flex; align-items: center; }
        .section-header .icon { margin-right: 10px; font-size: 24px; }
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
            <div class="status-badge">OPERATION COMPLETED</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <strong>Success:</strong> {{ session('success') }}
            </div>
        @endif

        <div class="info-box">
            <h3>🎯 What Happened:</h3>
            <p>Your AI-suggested fix has been successfully applied to the file. A timestamped backup was automatically created before making any changes to ensure your data is completely safe.</p>
        </div>

        <div class="updated-file-section">
            <div class="section-header">
                <span class="icon">📝</span>
                <span>Updated File Information</span>
            </div>
            <p><strong>File Path:</strong></p>
            <div class="file-path">{{ $errorFile }}</div>
            <p><strong>Fixed Line Number:</strong> <span class="line-badge">Line {{ $errorLine }}</span></p>
            <p><strong>Status:</strong> <span class="status-badge">✅ SUCCESSFULLY UPDATED</span></p>
        </div>

        @if(session('updated_content'))
            <div class="updated-file-section">
                <h3>📝 Updated File Content:</h3>
                <div class="file-content-preview">
                    <pre><code>{{ session('updated_content') }}</code></pre>
                </div>
                <p class="file-note"><em>This is the current state of your file after the AI fix was applied.</em></p>
            </div>
        @endif

        @if($hasBackup)
            <div class="backup-section">
                <div class="section-header">
                    <span class="icon">💾</span>
                    <span>Backup Information</span>
                </div>
                <p><strong>Backup File Name:</strong></p>
                <div class="backup-path">{{ $backupFile }}</div>
                <p><strong>Full Backup Path:</strong></p>
                <div class="backup-path">{{ storage_path('app/ai-error-handler/backups/' . $backupFile) }}</div>
                <div class="alert alert-info" style="margin-top: 15px;">
                    <strong>📋 Important:</strong> This backup contains the original version of your file before the AI fix was applied. You can restore it anytime if the fix doesn't work as expected.
                </div>
            </div>

            <div class="actions">
                <form method="POST" action="/ai-error-handler/restore" style="display: inline;">
                    @csrf
                    <input type="hidden" name="error_file" value="{{ $errorFile }}">
                    <input type="hidden" name="backup_file" value="{{ $backupFile }}">
                    <button type="submit" class="warning" onclick="return confirm('🚨 Are you sure you want to restore the file from backup?\n\nThis will:\n• Overwrite all current changes\n• Restore the original file content\n• Delete this backup file\n\nThis action cannot be undone!')">
                        🔄 Restore from Backup
                    </button>
                </form>
                <div style="margin-top: 15px; text-align: center;">
                    <small style="color: #6c757d;">⚠️ Use restore only if the AI fix caused issues</small>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                <strong>Note:</strong> Backup file not found. It may have been deleted or moved.
            </div>
        @endif

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url()->previous() }}" class="back-link">← Go Back</a>
        </div>
    </div>
</body>
</html>
