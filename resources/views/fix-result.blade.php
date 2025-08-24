<!DOCTYPE html>
<html>
<head>
    <title>AI Fix Result</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        pre { background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; overflow-x: auto; }
        a { display: inline-block; margin-top: 15px; color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h2>✅ AI Fix Suggestion</h2>
        <pre>{{ $aiFix }}</pre>
        <p>File: {{ $errorFile }} (Line: {{ $errorLine }})</p>
        <a href="{{ url()->previous() }}">🔄 Go back and test again</a>
    </div>
</body>
</html>
