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
        .explanation-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 15px; margin: 15px 0; }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <h2>AI Fix Suggestion</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        <div id="alert-container"></div>
        
        @if($hasBackup)
            <div class="backup-info">
                <strong>Backup Available:</strong> A backup file exists for this file. You can restore it if needed.
                <form method="POST" action="/ai-error-handler/restore" style="display: inline;">
                    @csrf
                    <input type="hidden" name="error_file" value="{{ $errorFile }}">
                    <input type="hidden" name="backup_file" value="{{ $backupFileName }}">
                    <button type="submit" class="warning" onclick="return confirm('Are you sure you want to restore the file from backup? This will overwrite current changes.')">
                        Restore from Backup
                    </button>
                </form>
            </div>
        @endif
        
        <div class="alert alert-warning">
            <strong>Warning:</strong> Always review AI suggestions before applying them. Create a backup of your files before making changes.
        </div>
        
        <div class="alert alert-info">
            <strong>Note:</strong> Only executable PHP code is extracted from AI responses. Explanatory text and comments are automatically filtered out for safety.
        </div>
        
        <h3>Error Analysis & Explanation:</h3>
        <div class="explanation-box">
            <pre>{{ $explanation }}</pre>
        </div>
        
        <h3>Executable Code Fixes:</h3>
        <pre>{{ $aiFix }}</pre>
        
        @if(!empty($extractedFixes))
            <h3>Extracted Code Fixes:</h3>
            @foreach($extractedFixes as $fix)
                <div class="code-block">
                    <h4>Fix {{ $fix['index'] }} ({{ ucfirst(str_replace('_', ' ', $fix['type'])) }}):</h4>
                    <pre><code>{{ $fix['content'] }}</code></pre>
                    <div class="code-actions">
                        <form method="POST" action="/ai-error-handler/apply-fix" style="display: inline;">
                            @csrf
                            <input type="hidden" name="error_file" value="{{ $errorFile }}">
                            <input type="hidden" name="error_line" value="{{ $errorLine }}">
                            <input type="hidden" name="fix_code" value="{{ $fix['content'] }}">
                            <input type="hidden" name="fix_index" value="{{ $fix['index'] }}">
                            <button type="submit" class="warning" onclick="return confirm('Are you sure you want to apply this fix? A backup will be created automatically.')">
                                Apply This Fix
                            </button>
                        </form>
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
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Create a temporary alert for copy success
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.innerHTML = 'Code copied to clipboard!';
                alert.style.position = 'fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }).catch(() => {
                // Create a temporary alert for copy failure
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger';
                alert.innerHTML = 'Failed to copy code to clipboard';
                alert.style.position = 'fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            });
        }
    </script>
</body>
</html>
