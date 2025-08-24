<!DOCTYPE html>
<html>
<head>
    <title>AI Fix Result</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #6366f1;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><radialGradient id="a" cx="50%" cy="50%" r="50%"><stop offset="0%" stop-color="rgba(255,255,255,0.1)"/><stop offset="100%" stop-color="rgba(255,255,255,0)"/></radialGradient></defs><circle cx="20" cy="20" r="2" fill="url(%23a)"/><circle cx="80" cy="40" r="1.5" fill="url(%23a)"/><circle cx="40" cy="80" r="1" fill="url(%23a)"/><circle cx="90" cy="90" r="2.5" fill="url(%23a)"/><circle cx="10" cy="60" r="1.5" fill="url(%23a)"/></svg>') repeat;
            animation: float 20s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .container {
            max-width: 1000px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            margin-bottom: 25px;
            text-align: center;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
        }
        
        .header h1 {
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .header .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
            font-weight: 300;
        }
        
        .alert {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 18px 22px;
            margin: 15px 0;
            color: white;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border-color: rgba(40, 167, 69, 0.3);
            color: #d4edda;
        }
        
        .alert-danger {
            background: rgba(220, 53, 69, 0.2);
            border-color: rgba(220, 53, 69, 0.3);
            color: #f8d7da;
        }
        
        .alert-warning {
            background: rgba(255, 193, 7, 0.2);
            border-color: rgba(255, 193, 7, 0.3);
            color: #fff3cd;
        }
        
        .alert-info {
            background: rgba(23, 162, 184, 0.2);
            border-color: rgba(23, 162, 184, 0.3);
            color: #bee5eb;
        }
        
        .backup-info {
            background: rgba(23, 162, 184, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(23, 162, 184, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            color: #bee5eb;
        }
        
        .explanation-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .explanation-section h3 {
            color: white;
            font-size: 1.4rem;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .explanation-box {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 20px;
        }
        
        .explanation-box pre {
            color: #e2e8f0;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.9rem;
            line-height: 1.6;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        
        .code-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 25px;
            margin: 25px 0;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }
        
        .code-section h3 {
            color: white;
            font-size: 1.4rem;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .code-block {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }
        
        .code-block::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #06b6d4;
            border-radius: 15px 15px 0 0;
        }
        
        .code-block h4 {
            color: #a7f3d0;
            font-size: 1.1rem;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .code-block pre {
            background: rgba(0, 0, 0, 0.4);
            border-radius: 10px;
            padding: 15px;
            overflow-x: auto;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .code-block pre code {
            color: #f8f8f2;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.9rem;
            line-height: 1.5;
        }
        
        .code-actions {
            margin-top: 15px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        button {
            background: #3b82f6;
            border: none;
            color: white;
            padding: 12px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s ease;
        }
        
        button:hover::before {
            left: 100%;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(59, 130, 246, 0.4);
        }
        
        button.warning {
            background: #f59e0b;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }
        
        button.warning:hover {
            box-shadow: 0 12px 25px rgba(245, 158, 11, 0.4);
        }
        
        button.success {
            background: #10b981;
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        }
        
        button.success:hover {
            box-shadow: 0 12px 25px rgba(16, 185, 129, 0.4);
        }
        
        .no-fixes {
            background: rgba(255, 193, 7, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            color: #fff3cd;
            margin: 25px 0;
        }
        
        .file-info {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 15px;
            padding: 20px;
            margin: 25px 0;
            color: white;
            font-weight: 500;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .back-link:hover {
            color: white;
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .container {
                padding: 0 10px;
            }
            
            .header {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 1.8rem;
            }
            
            .code-actions {
                flex-direction: column;
            }
            
            button {
                width: 100%;
            }
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🧠 AI Fix Analysis</h1>
            <p class="subtitle">Intelligent error diagnosis & code solutions</p>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success">
                <strong>✅ Success:</strong> {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                <strong>❌ Error:</strong> {{ session('error') }}
            </div>
        @endif
        
        @if($hasBackup)
            <div class="backup-info">
                <strong>💾 Backup Available:</strong> A backup file exists for this file. You can restore it if needed.
                <form method="POST" action="/ai-error-handler/restore" style="display: inline; margin-left: 15px;">
                    @csrf
                    <input type="hidden" name="error_file" value="{{ $errorFile }}">
                    <input type="hidden" name="backup_file" value="{{ $backupFileName }}">
                    <button type="submit" class="warning" onclick="return confirm('Are you sure you want to restore the file from backup? This will overwrite current changes.')">
                        🔄 Restore from Backup
                    </button>
                </form>
            </div>
        @endif
        
        <div class="alert alert-warning">
            <strong>⚠️ Important:</strong> Always review AI suggestions before applying them. Create a backup of your files before making changes.
        </div>
        
        <div class="alert alert-info">
            <strong>🔒 Safety Note:</strong> Only executable PHP code is extracted from AI responses. Explanatory text and comments are automatically filtered out for safety.
        </div>
        
        <div class="explanation-section">
            <h3>🔍 Error Analysis & Explanation</h3>
            <div class="explanation-box">
                <pre>{{ $explanation }}</pre>
            </div>
        </div>
        
        <div class="code-section">
            <h3>💻 Raw AI Response</h3>
            <div class="explanation-box">
                <pre>{{ $aiFix }}</pre>
            </div>
        </div>
        
        @if(!empty($extractedFixes))
            <div class="code-section">
                <h3>🛠️ Extracted Code Fixes</h3>
                @foreach($extractedFixes as $fix)
                    <div class="code-block">
                        <h4>🔧 Fix {{ $fix['index'] }} - {{ ucfirst(str_replace('_', ' ', $fix['type'])) }}</h4>
                        <pre><code>{{ $fix['content'] }}</code></pre>
                        <div class="code-actions">
                            <form method="POST" action="/ai-error-handler/apply-fix" style="display: inline;">
                                @csrf
                                <input type="hidden" name="error_file" value="{{ $errorFile }}">
                                <input type="hidden" name="error_line" value="{{ $errorLine }}">
                                <input type="hidden" name="fix_code" value="{{ $fix['content'] }}">
                                <input type="hidden" name="fix_index" value="{{ $fix['index'] }}">
                                <button type="submit" class="warning" onclick="return confirm('🚨 Apply this fix?\n\n✅ A backup will be created automatically\n⚠️ This will modify your file\n🔄 You can restore from backup if needed\n\nProceed?')">
                                    🚀 Apply This Fix
                                </button>
                            </form>
                            <button type="button" onclick="copyToClipboard(this)" class="success" data-code="{{ base64_encode($fix['content']) }}">
                                📋 Copy Code
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-fixes">
                <h4>🤔 No Code Fixes Detected</h4>
                <p>The AI response doesn't contain recognizable code blocks. Please review the explanation above for manual guidance.</p>
            </div>
        @endif
        
        <div class="file-info">
            <strong>📁 Target File:</strong> {{ $errorFile }} <span style="opacity: 0.8;">(Line: {{ $errorLine }})</span>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ url()->previous() }}" class="back-link">← Back to Error Details</a>
        </div>
    </div>

    <script>
        function copyToClipboard(button) {
            // Get the base64 encoded code from data attribute
            const encodedCode = button.getAttribute('data-code');
            
            // Decode the base64 content
            const text = atob(encodedCode);
            
            // Copy to clipboard
            navigator.clipboard.writeText(text).then(() => {
                // Create a temporary alert for copy success
                const alert = document.createElement('div');
                alert.className = 'alert alert-success';
                alert.innerHTML = '✅ Code copied to clipboard!';
                alert.style.position = 'fixed';
                alert.style.top = '20px';
                alert.style.right = '20px';
                alert.style.zIndex = '9999';
                alert.style.borderRadius = '5px';
                alert.style.padding = '10px 15px';
                alert.style.fontWeight = 'bold';
                document.body.appendChild(alert);
                
                setTimeout(() => {
                    alert.remove();
                }, 3000);
            }).catch((error) => {
                console.error('Copy failed:', error);
                
                // Fallback: Create a textarea and select the text
                const textarea = document.createElement('textarea');
                textarea.value = text;
                textarea.style.position = 'absolute';
                textarea.style.left = '-9999px';
                document.body.appendChild(textarea);
                textarea.select();
                
                try {
                    document.execCommand('copy');
                    
                    // Success with fallback method
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.innerHTML = '✅ Code copied to clipboard! (fallback method)';
                    alert.style.position = 'fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.style.borderRadius = '5px';
                    alert.style.padding = '10px 15px';
                    alert.style.fontWeight = 'bold';
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 3000);
                } catch (fallbackError) {
                    // Both methods failed
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-danger';
                    alert.innerHTML = '❌ Failed to copy code to clipboard. Please copy manually.';
                    alert.style.position = 'fixed';
                    alert.style.top = '20px';
                    alert.style.right = '20px';
                    alert.style.zIndex = '9999';
                    alert.style.borderRadius = '5px';
                    alert.style.padding = '10px 15px';
                    alert.style.fontWeight = 'bold';
                    document.body.appendChild(alert);
                    
                    setTimeout(() => {
                        alert.remove();
                    }, 5000);
                }
                
                textarea.remove();
            });
        }
    </script>
</body>
</html>
