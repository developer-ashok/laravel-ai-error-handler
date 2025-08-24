<!DOCTYPE html>
<html>
<head>
    <title>AI Error Handler</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .warning { color: #b71c1c; margin-bottom: 20px; font-weight: bold; }
        .error { background: #ffeeee; padding: 15px; border: 1px solid #f5c6cb; border-radius: 5px; margin-bottom: 20px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h2>🚨 Laravel AI Error Handler</h2>
        <p class="warning">⚠️ Please only use this AI fix if you are confident. Do not rely too much on complex system logic.</p>

        <div class="error">
            <p><strong>Error:</strong> {{ $errorMessage }}</p>
            <p><strong>File:</strong> {{ $errorFile }}</p>
            <p><strong>Line:</strong> {{ $errorLine }}</p>
        </div>

        <form method="POST" action="/ai-error-handler/fix">
            @csrf
            <input type="hidden" name="error_message" value="{{ $errorMessage }}">
            <input type="hidden" name="error_file" value="{{ $errorFile }}">
            <input type="hidden" name="error_line" value="{{ $errorLine }}">
            <button type="submit">🔧 Ask AI to Fix</button>
        </form>
    </div>
</body>
</html>
