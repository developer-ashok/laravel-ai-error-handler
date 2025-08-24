# Laravel AI Error Handler

A Laravel package that integrates with Perplexity AI to provide intelligent error analysis and debugging suggestions. When exceptions occur in your Laravel application, this package captures them and uses AI to generate actionable debugging insights.

## Features

- **🤖 Dual AI Analysis**: Two-step AI processing for comprehensive error understanding
  - **Detailed Error Explanation**: AI analyzes and explains what went wrong and why
  - **Clean Code Fixes**: AI generates executable PHP code to fix the issue
- **🔧 Automatic Code Fixing**: Apply AI-suggested fixes directly to your files
- **💾 Smart Backup System**: Automatic timestamped backups before applying any fixes
- **🔄 One-Click Restore**: Easily restore files from backups if fixes don't work
- **📋 Copy to Clipboard**: Copy generated code fixes with one click
- **🎨 Beautiful Success Pages**: Professional interface showing fix results and backup details
- **🔍 Comprehensive Logging**: Debug-friendly logging for troubleshooting
- **⚡ Multiple Data Passing Methods**: Robust session handling with URL parameter fallbacks
- **🛡️ Safe Code Extraction**: Filters out explanatory text, only applies executable PHP code
- **📱 Responsive Design**: Clean, modern UI that works on all devices

## Requirements

- PHP >= 8.1
- Laravel 8.x or higher
- Perplexity AI API key
- Guzzle HTTP client

## Installation

### 1. Install via Composer

```bash
composer require coderubix/laravel-ai-error-handler:dev-main
```

**Note**: Since this package is in development, use `:dev-main` to allow development versions.

#### Alternative: Local Development Installation

If you're developing this package locally, you can also add it as a path repository in your Laravel project's `composer.json`:

```json
{
    "repositories": [
        {
            "type": "path",
            "url": "/path/to/laravel-ai-error-handler"
        }
    ]
}
```

Then install without version constraint:
```bash
composer require coderubix/laravel-ai-error-handler
```

### 2. Publish Configuration

```bash
php artisan vendor:publish --tag=ai-error-handler-config
```

Optionally, you can also publish the views for customization:

```bash
php artisan vendor:publish --tag=ai-error-handler-views
```

### 3. Environment Configuration

Add your Perplexity API key to your `.env` file:

```env
PERPLEXITY_API_KEY=your_api_key_here
AI_ERROR_HANDLER_ENABLED=true
AI_ERROR_HANDLER_MODEL=llama-3.1-sonar-large-128k-online
```

## Configuration

The package configuration file (`config/ai-error-handler.php`) contains:

```php
return [
    'enabled' => env('AI_ERROR_HANDLER_ENABLED', false),
    'perplexity_api_key' => env('PERPLEXITY_API_KEY', ''),
    'model' => env('AI_ERROR_HANDLER_MODEL', 'sonar'),
];
```

### Configuration Options

- **enabled**: Enable/disable the AI error handler (default: false)
- **perplexity_api_key**: Your Perplexity AI API key
- **model**: AI model to use for error analysis (default: 'sonar')

### Available Models

You can configure any Perplexity AI model by setting the `AI_ERROR_HANDLER_MODEL` environment variable. The default is `llama-3.1-sonar-large-128k-online`.

**Popular Models:**
- `llama-3.1-sonar-large-128k-online` (default) - Balanced performance and accuracy
- `llama-3.1-sonar-small-128k-online` - Faster responses, lower cost
- `llama-3.1-sonar-huge-128k-online` - Maximum accuracy and detail

Simply set the full model name in your `.env` file.

## Usage

### 🚀 Automatic Error Handling

Once enabled, the package automatically intercepts all Laravel exceptions and displays an AI-powered error analysis page with:

1. **Error Details**: Shows the error message, file, and line number
2. **AI Analysis Button**: Click "Get AI Fix" to analyze the error
3. **Dual AI Processing**: 
   - **Step 1**: Detailed explanation of what went wrong and why
   - **Step 2**: Clean, executable PHP code to fix the issue
4. **Apply Fixes**: Apply suggested fixes directly with automatic backup
5. **Success Page**: Beautiful results page with file details and restore options

### 🔧 AI Fix Workflow

1. **Error Occurs** → Laravel shows AI error handler page
2. **Click "Get AI Fix"** → AI analyzes the error (dual API calls)
3. **Review Suggestions** → See detailed explanation + code fixes
4. **Apply Fix** → Click "Apply This Fix" (creates automatic backup)
5. **Success Page** → See results, file details, backup info, restore option

### 📋 Copy & Manual Application

If you prefer not to auto-apply fixes:

1. **Copy Code**: Use the "Copy Code" button for any suggested fix
2. **Manual Review**: Review the code before applying
3. **Manual Application**: Apply the changes yourself in your IDE

### 🔄 Backup & Restore System

- **Automatic Backups**: Created before every fix with timestamp
- **Backup Location**: `storage/app/ai-error-handler/backups/`
- **One-Click Restore**: Restore from backup if fix doesn't work
- **Backup Management**: Backups are automatically cleaned up after restore

### 📍 Available Routes

```
POST /ai-error-handler/fix        # AI analysis and fix generation
POST /ai-error-handler/apply-fix  # Apply a specific fix
POST /ai-error-handler/restore    # Restore from backup
GET  /ai-error-handler/success    # Success page after fix/restore
```

## How It Works

### 🔄 Complete Error Resolution Flow

1. **Exception Capture**: `AIExceptionHandler` intercepts Laravel exceptions
2. **Error Display**: Shows user-friendly error page with error details
3. **AI Analysis Request**: User clicks "Get AI Fix" button
4. **Dual AI Processing**:
   - **First API Call**: Detailed error explanation and analysis
   - **Second API Call**: Clean, executable PHP code fixes (no explanatory text)
5. **Smart Code Extraction**: Filters and extracts only valid PHP code
6. **Results Display**: Shows both explanation and actionable code fixes
7. **Fix Application**: User can apply fixes with automatic backup creation
8. **Success Feedback**: Professional success page with file details and restore options

### 🤖 AI Processing Details

- **Two-Step Analysis**: Separate API calls for explanation vs. code generation
- **Safe Code Generation**: AI instructed to generate only executable PHP (no `<?php` tags)
- **Content Filtering**: Automatic removal of explanatory text from code fixes
- **Multiple Fix Options**: AI can provide several different solutions
- **Backup Safety**: Every fix creates a timestamped backup automatically

## Views

The package includes three main Blade views:

- **`error.blade.php`**: Initial error display with "Get AI Fix" button
- **`fix-result.blade.php`**: Shows dual AI analysis (explanation + code fixes) with apply/copy options
- **`success.blade.php`**: Beautiful success page with file details, backup info, and restore functionality

### 🎨 View Features

- **Modern Design**: Clean, professional interface with gradients and icons
- **Responsive Layout**: Works perfectly on desktop, tablet, and mobile
- **Copy to Clipboard**: One-click code copying with fallback methods
- **Form-Based Actions**: Reliable PHP forms instead of complex JavaScript
- **Visual Feedback**: Success/error alerts with clear messaging
- **Backup Details**: Complete backup file paths and locations

## Architecture

### 🏗️ Service Provider (`AIErrorHandlerServiceProvider`)

- **Configuration Management**: Publishes and merges config files
- **View Publishing**: Publishes customizable Blade views
- **Route Loading**: Loads package routes automatically
- **Service Registration**: Registers `BackupService` and `AIFixParser` singletons
- **Exception Handler Binding**: Conditionally replaces Laravel's exception handler

### 🛡️ Exception Handler (`AIExceptionHandler`)

- **Exception Interception**: Captures all Laravel exceptions when enabled
- **Custom Error Views**: Renders AI-powered error pages instead of Laravel's default
- **Context Passing**: Provides error details to views for AI analysis

### 🎮 Controller (`AIErrorFixController`)

- **Dual AI Processing**: Manages two separate API calls to Perplexity AI
- **Code Extraction**: Uses `AIFixParser` to extract clean PHP code from AI responses
- **File Operations**: Handles reading, writing, and backing up files safely
- **Session Management**: Multiple methods for reliable data passing (session + URL params)
- **Backup Management**: Integrates with `BackupService` for automatic file safety

### 🔧 Services

- **`BackupService`**: Creates timestamped backups, handles restore operations
- **`AIFixParser`**: Extracts and validates PHP code from AI responses, applies fixes intelligently

## Security Considerations

### 🔒 File Safety
- **Automatic Backups**: Every fix creates a timestamped backup before changes
- **File Validation**: Checks file existence and write permissions before applying fixes
- **Backup Restore**: One-click restore if fixes cause issues
- **Safe Code Extraction**: Filters out non-PHP content from AI responses

### 🛡️ AI Safety
- **Code Review**: Always review AI suggestions before applying them
- **No PHP Tags**: AI is instructed to never include `<?php` tags to prevent file corruption
- **Content Filtering**: Explanatory text is automatically filtered from code fixes
- **Multiple Options**: AI provides multiple fix options for user choice

### 🔐 API Security
- **API Key Protection**: Ensure your Perplexity API key is properly secured in `.env`
- **Error Information**: Be cautious about exposing sensitive error details in production
- **Environment Variables**: Use proper environment configuration for sensitive data

## Customization

### Custom Views

You can publish and customize the views:

```bash
php artisan vendor:publish --tag=ai-error-handler-views
```

This will publish the views to `resources/views/vendor/ai-error-handler/` where you can customize them.

### Custom Error Handling

Extend the `AIExceptionHandler` class to add custom logic:

```php
use LaravelAIErrorHandler\AIExceptionHandler;

class CustomAIExceptionHandler extends AIExceptionHandler
{
    public function render($request, Throwable $e)
    {
        // Add custom logic here
        return parent::render($request, $e);
    }
}
```

## Troubleshooting

### 🐛 Common Issues

1. **API Key Not Working**: 
   - Verify your Perplexity API key is correct in `.env`
   - Check if the key has proper permissions

2. **Handler Not Intercepting**: 
   - Ensure `AI_ERROR_HANDLER_ENABLED=true` in `.env`
   - Verify the service provider is registered

3. **Configuration Not Published**: 
   - Run `php artisan vendor:publish --tag=ai-error-handler-config`
   - Check if `config/ai-error-handler.php` exists

4. **Session Data Not Working**:
   - Package uses multiple fallback methods (session + URL parameters)
   - Check Laravel logs for session debugging information
   - Verify Laravel session configuration is working

5. **Copy to Clipboard Not Working**:
   - Package includes fallback methods for older browsers
   - Check browser console for JavaScript errors
   - Try the manual copy approach if automatic fails

6. **Backup/Restore Issues**:
   - Ensure `storage/app/ai-error-handler/backups/` directory is writable
   - Check file permissions for the files being fixed
   - Verify sufficient disk space for backups

### 🔍 Debug Mode

Enable comprehensive debugging:

```env
APP_DEBUG=true
LOG_LEVEL=debug
```

The package logs detailed information about:
- AI API calls and responses
- Session data setting and retrieval  
- File operations and backups
- Code extraction and validation

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Support

For support and questions:
- Create an issue on GitHub
- Check the documentation
- Review the configuration options

## Disclaimer

**Important**: This package provides AI-powered debugging suggestions. Always review and test AI-generated code before implementing it in production. The suggestions are meant to assist with debugging and should not be relied upon as the sole solution for complex system logic issues.

## Changelog

### Version 1.0.0 (Latest)
- **🤖 Dual AI Analysis**: Two-step AI processing for comprehensive error understanding
- **🔧 Automatic Code Fixing**: Apply AI-suggested fixes directly to files with backup system
- **💾 Smart Backup System**: Timestamped backups with one-click restore functionality
- **📋 Enhanced Copy to Clipboard**: Robust copy functionality with base64 encoding and fallbacks
- **🎨 Beautiful Success Pages**: Professional UI showing fix results and backup details
- **⚡ Multi-Method Data Passing**: Session + URL parameters for reliable data flow
- **🛡️ Safe Code Extraction**: Intelligent filtering of AI responses for executable PHP only
- **🔍 Comprehensive Logging**: Debug-friendly logging for troubleshooting
- **🎨 Modern UI**: Responsive design with gradients, icons, and professional styling
- **📱 Mobile-Friendly**: Clean interface that works on all devices

### Core Services Added
- **BackupService**: Automatic file backup and restore management
- **AIFixParser**: Smart code extraction and validation from AI responses

### Enhanced Security
- **File Safety**: Automatic backups before any changes
- **Code Validation**: Filters out non-PHP content from AI responses  
- **Permission Checks**: Validates file access before modifications
- **No PHP Tags**: AI instructed to avoid `<?php` tags in generated code
