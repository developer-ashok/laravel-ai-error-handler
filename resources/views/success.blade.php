<!DOCTYPE html>
<html>
<head>
    <title>AI Fix Applied Successfully</title>
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
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .success-header {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 25px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .success-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #06b6d4;
            border-radius: 25px 25px 0 0;
        }
        
        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
            animation: pulse 2s ease-in-out infinite;
        }
        
        .success-header h1 {
            color: white;
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .status-badge {
            background: #06b6d4;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 5px 15px rgba(6, 182, 212, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .alert {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 20px 25px;
            margin: 20px 0;
            color: white;
            font-weight: 500;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .alert-success {
            background: rgba(40, 167, 69, 0.2);
            border-color: rgba(40, 167, 69, 0.3);
            color: #d4edda;
        }
        
        .alert-info {
            background: rgba(23, 162, 184, 0.2);
            border-color: rgba(23, 162, 184, 0.3);
            color: #bee5eb;
        }
        
        .card-section {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            margin: 25px 0;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .card-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: #3b82f6;
            border-radius: 20px 20px 0 0;
        }
        
        .section-header {
            color: white;
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .section-header .icon {
            margin-right: 12px;
            font-size: 1.6rem;
        }
        
        .file-path, .backup-path {
            background: rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px 20px;
            margin: 12px 0;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 0.9rem;
            word-break: break-all;
            color: #e2e8f0;
        }
        
        .backup-path {
            background: rgba(23, 162, 184, 0.2);
            border-color: rgba(23, 162, 184, 0.3);
            color: #bee5eb;
        }
        
        .line-badge {
            background: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 3px 10px rgba(59, 130, 246, 0.3);
        }
        
        .status-indicator {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.4);
        }
        
        .actions {
            margin-top: 30px;
            text-align: center;
        }
        
        button {
            background: #f59e0b;
            border: none;
            color: white;
            padding: 15px 30px;
            border-radius: 15px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
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
            box-shadow: 0 15px 35px rgba(245, 158, 11, 0.6);
        }
        
        .warning-note {
            text-align: center;
            margin-top: 15px;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            font-style: italic;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 30px;
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
            
            .success-header {
                padding: 25px;
            }
            
            .success-header h1 {
                font-size: 1.8rem;
            }
            
            .card-section {
                padding: 20px;
            }
        }
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

        <div class="alert alert-info">
            <strong>🎯 What Happened:</strong> Your AI-suggested fix has been successfully applied to the file. A timestamped backup was automatically created before making any changes to ensure your data is completely safe.
        </div>

        <div class="card-section">
            <div class="section-header">
                <span class="icon">📝</span>
                <span>Updated File Information</span>
            </div>
            <p style="color: rgba(255,255,255,0.9); margin-bottom: 10px;"><strong>File Path:</strong></p>
            <div class="file-path">{{ $errorFile }}</div>
            <p style="color: rgba(255,255,255,0.9); margin: 15px 0 10px 0;"><strong>Fixed Line Number:</strong> <span class="line-badge">Line {{ $errorLine }}</span></p>
            <p style="color: rgba(255,255,255,0.9); margin: 15px 0 10px 0;"><strong>Status:</strong> <span class="status-indicator">✅ SUCCESSFULLY UPDATED</span></p>
        </div>

        @if(isset($updatedContent) && !empty($updatedContent))
            <div class="card-section">
                <div class="section-header">
                    <span class="icon">📄</span>
                    <span>Updated File Content</span>
                </div>
                <div style="background: rgba(0,0,0,0.3); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 20px;">
                    <pre style="color: #e2e8f0; font-family: 'Monaco', 'Menlo', monospace; font-size: 0.9rem; line-height: 1.6; white-space: pre-wrap; word-wrap: break-word;"><code>{{ $updatedContent }}</code></pre>
                </div>
                <p style="text-align: center; margin-top: 15px; color: rgba(255,255,255,0.8); font-style: italic; font-size: 0.9rem;">This is the current state of your file after the AI fix was applied.</p>
            </div>
        @endif

        @if($hasBackup)
            <div class="card-section">
                <div class="section-header">
                    <span class="icon">💾</span>
                    <span>Backup Information</span>
                </div>
                <p style="color: rgba(255,255,255,0.9); margin-bottom: 10px;"><strong>Backup File Name:</strong></p>
                <div class="backup-path">{{ $backupFile }}</div>
                <p style="color: rgba(255,255,255,0.9); margin: 15px 0 10px 0;"><strong>Full Backup Path:</strong></p>
                <div class="backup-path">{{ storage_path('app/ai-error-handler/backups/' . $backupFile) }}</div>
                <div class="alert alert-info" style="margin-top: 20px;">
                    <strong>📋 Important:</strong> This backup contains the original version of your file before the AI fix was applied. You can restore it anytime if the fix doesn't work as expected.
                </div>

                <div class="actions">
                    <form method="POST" action="/ai-error-handler/restore">
                        @csrf
                        <input type="hidden" name="error_file" value="{{ $errorFile }}">
                        <input type="hidden" name="backup_file" value="{{ $backupFile }}">
                        <button type="submit" onclick="return confirm('🚨 Are you sure you want to restore the file from backup?\n\nThis will:\n• Overwrite all current changes\n• Restore the original file content\n• Delete this backup file\n\nThis action cannot be undone!')">
                            🔄 Restore from Backup
                        </button>
                    </form>
                    <div class="warning-note">
                        ⚠️ Use restore only if the AI fix caused issues
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning">
                <strong>⚠️ Note:</strong> Backup file not found. It may have been deleted or moved.
            </div>
        @endif

        <div style="text-align: center; margin-top: 40px;">
            <a href="{{ url()->previous() }}" class="back-link">← Go Back</a>
        </div>
    </div>
</body>
</html>
