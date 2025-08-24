<!DOCTYPE html>
<html>
<head>
    <title>AI Error Handler</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
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
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: white;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        
        .header .subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
            font-weight: 300;
        }
        
        .warning {
            background: rgba(255, 193, 7, 0.2);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 193, 7, 0.3);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            color: #fff3cd;
            text-align: center;
            font-weight: 500;
        }
        
        .warning .icon {
            font-size: 1.5rem;
            margin-bottom: 10px;
            display: block;
        }
        
        .error-card {
            background: rgba(220, 53, 69, 0.15);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(220, 53, 69, 0.3);
            border-radius: 18px;
            padding: 25px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .error-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #ef4444;
            border-radius: 18px 18px 0 0;
        }
        
        .error-detail {
            margin-bottom: 15px;
            color: white;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        
        .error-detail:last-child {
            margin-bottom: 0;
        }
        
        .error-label {
            font-weight: 600;
            color: #ffcdd2;
            min-width: 60px;
            font-size: 0.9rem;
        }
        
        .error-value {
            flex: 1;
            font-family: 'Monaco', 'Menlo', monospace;
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            word-break: break-all;
            font-size: 0.9rem;
        }
        
        .action-section {
            text-align: center;
        }
        
        .ai-button {
            background: #3b82f6;
            border: none;
            color: white;
            padding: 18px 35px;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.4);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            overflow: hidden;
        }
        
        .ai-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.2);
            transition: left 0.5s ease;
        }
        
        .ai-button:hover::before {
            left: 100%;
        }
        
        .ai-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(59, 130, 246, 0.6);
        }
        
        .ai-button:active {
            transform: translateY(0);
        }
        
        @media (max-width: 768px) {
            .container {
                margin: 20px;
                padding: 25px;
            }
            
            .header h1 {
                font-size: 2rem;
            }
            
            .error-value {
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🤖 AI Error Handler</h1>
            <p class="subtitle">Intelligent error analysis & fixing</p>
        </div>
        
        <div class="warning">
            <span class="icon">⚠️</span>
            Please review AI suggestions carefully before applying them. This tool assists with debugging but should not replace careful code review.
        </div>

        <div class="error-card">
            <div class="error-detail">
                <span class="error-label">Error:</span>
                <div class="error-value">{{ $errorMessage }}</div>
            </div>
            <div class="error-detail">
                <span class="error-label">File:</span>
                <div class="error-value">{{ $errorFile }}</div>
            </div>
            <div class="error-detail">
                <span class="error-label">Line:</span>
                <div class="error-value">{{ $errorLine }}</div>
            </div>
        </div>

        <div class="action-section">
            <form method="POST" action="/ai-error-handler/fix">
                @csrf
                <input type="hidden" name="error_message" value="{{ $errorMessage }}">
                <input type="hidden" name="error_file" value="{{ $errorFile }}">
                <input type="hidden" name="error_line" value="{{ $errorLine }}">
                <button type="submit" class="ai-button">
                    🧠 Get AI Fix Suggestions
                </button>
            </form>
        </div>
    </div>
</body>
</html>
