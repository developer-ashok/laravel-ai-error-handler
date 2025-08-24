<!DOCTYPE html>
<html>
<head>
    <title>AI Fix Result</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        pre { background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; margin: 15px 0; }
        .code-block { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; margin: 10px 0; position: relative; }
        .code-block pre { background: #272822; margin: 10px 0; }
        .code-actions { margin-top: 10px; }
        button { background: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; margin-right: 10px; }
        button:hover { background: #0056b3; }
        button.success { background: #28a745; }
        button.danger { background: #dc3545; }
        button.warning { background: #ffc107; color: #212529; }
        .alert { padding: 10px 15px; border-radius: 4px; margin: 10px 0; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeaa7; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .backup-info { background: #e7f3ff; padding: 10px; border-radius: 4px; margin: 10px 0; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <h2>AI Fix Suggestion</h2>
        
        <div id="alert-container"></div>
        
        @if($hasBackup)
            <div class="backup-info">
                <strong>Backup Available:</strong> A backup file exists for this file. You can restore it if needed.
                <button type="button" class="warning" onclick="restoreFile()">Restore from Backup</button>
            </div>
        @endif
        
        <div class="alert alert-warning">
            <strong>Warning:</strong> Always review AI suggestions before applying them. Create a backup of your files before making changes.
        </div>
        
        <h3>Full AI Response:</h3>
        <pre>{{ $aiFix }}</pre>
        
        @if(!empty($extractedFixes))
            <h3>Extracted Code Fixes:</h3>
            @foreach($extractedFixes as $fix)
                <div class="code-block">
                    <h4>Fix {{ $fix['index'] }} ({{ ucfirst(str_replace('_', ' ', $fix['type'])) }}):</h4>
                    <pre><code>{{ $fix['content'] }}</code></pre>
                    <div class="code-actions">
                        <button type="button" onclick="applyFix({{ $fix['index'] }}, '{{ addslashes($fix['content']) }}')">
                            Apply This Fix
                        </button>
                        <button type="button" onclick="copyToClipboard('{{ addslashes($fix['content']) }}')" class="success">
                            Copy Code
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="alert alert-warning">
                <strong>No Code Fixes Detected:</strong> The AI response doesn't contain recognizable code blocks. Please review the suggestion manually.
            </div>
        @endif
        
        <p><strong>File:</strong> {{ $errorFile }} (Line: {{ $errorLine }})</p>
        <a href="{{ url()->previous() }}">Go back and test again</a>
    </div>

    <script>
        const errorFile = '{{ $errorFile }}';
        const errorLine = {{ $errorLine }};
        const hasBackup = {{ $hasBackup ? 'true' : 'false' }};
        const backupFile = '{{ $hasBackup }}';
        
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alert-container');
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.innerHTML = message;
            alertContainer.appendChild(alert);
            
            setTimeout(() => {
                alert.remove();
            }, 5000);
        }
        
        function applyFix(fixIndex, fixCode) {
            if (!confirm('Are you sure you want to apply this fix? A backup will be created automatically.')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('error_file', errorFile);
            formData.append('error_line', errorLine);
            formData.append('fix_code', fixCode);
            formData.append('fix_index', fixIndex);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/ai-error-handler/apply-fix', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Refresh page to show backup option
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Network error: ' + error.message, 'danger');
            });
        }
        
        function restoreFile() {
            if (!confirm('Are you sure you want to restore the file from backup? This will overwrite current changes.')) {
                return;
            }
            
            const formData = new FormData();
            formData.append('error_file', errorFile);
            formData.append('backup_file', backupFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            fetch('/ai-error-handler/restore', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    // Refresh page to remove backup option
                    setTimeout(() => location.reload(), 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                showAlert('Network error: ' + error.message, 'danger');
            });
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                showAlert('Code copied to clipboard!', 'success');
            }).catch(() => {
                showAlert('Failed to copy code to clipboard', 'danger');
            });
        }
    </script>
</body>
</html>
