<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'AI Error Handler' }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; padding: 40px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input[type="text"], input[type="number"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; }
        textarea { height: 100px; resize: vertical; }
        button { background: #007bff; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        .alert { padding: 15px; border-radius: 4px; margin-bottom: 20px; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .info-box { background: #e7f3ff; padding: 15px; border-radius: 4px; margin-bottom: 20px; border-left: 4px solid #007bff; }
    </style>
</head>
<body>
    <div class="container">
        <h1>AI Error Handler</h1>
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="info-box">
            <strong>How it works:</strong> Provide the error details below and our AI will analyze the issue and suggest fixes. 
            The system will automatically create backups before applying any changes.
        </div>
        
        <form method="POST" action="/ai-error-handler/fix">
            @csrf
            
            <div class="form-group">
                <label for="error_message">Error Message:</label>
                <textarea name="error_message" id="error_message" placeholder="Paste the full error message here..." required>{{ old('error_message') }}</textarea>
            </div>
            
            <div class="form-group">
                <label for="error_file">File Path:</label>
                <input type="text" name="error_file" id="error_file" placeholder="e.g., /path/to/your/file.php" required value="{{ old('error_file') }}">
            </div>
            
            <div class="form-group">
                <label for="error_line">Line Number:</label>
                <input type="number" name="error_line" id="error_line" placeholder="e.g., 42" required value="{{ old('error_line') }}">
            </div>
            
            <button type="submit">Get AI Fix</button>
        </form>
        
        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ url()->previous() }}" style="color: #007bff; text-decoration: none;">← Go Back</a>
        </div>
    </div>
</body>
</html>
