# Laravel AI Error Handler

A Laravel package that integrates with Perplexity AI to provide intelligent error analysis and debugging suggestions. When exceptions occur in your Laravel application, this package captures them and uses AI to generate actionable debugging insights.

## Features

- **Automatic Error Capture**: Seamlessly intercepts Laravel exceptions
- **AI-Powered Analysis**: Uses Perplexity AI to analyze error context
- **Smart Suggestions**: Provides actionable debugging recommendations
- **Easy Integration**: Simple setup with minimal configuration
- **Customizable**: Configurable error handling behavior

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

### Automatic Error Handling

Once enabled, the package automatically intercepts all Laravel exceptions and displays an AI-powered error analysis page. Users can then request AI suggestions for fixing the error.

### Manual Error Analysis

You can also manually analyze exceptions in your code:

```php
use LaravelAIErrorHandler\AIErrorFixController;

try {
    // Your code that might throw an exception
} catch (\Throwable $e) {
    $controller = new AIErrorFixController();
    $request = request()->merge([
        'error_message' => $e->getMessage(),
        'error_file' => $e->getFile(),
        'error_line' => $e->getLine()
    ]);
    
    return $controller->fix($request);
}
```

### API Endpoint

The package provides a POST endpoint for manual error analysis:

```
POST /ai-error-handler/fix
```

**Request Parameters:**
- `error_message`: The error message
- `error_file`: The file where the error occurred
- `error_line`: The line number where the error occurred

## How It Works

1. **Exception Capture**: When an exception occurs, the `AIExceptionHandler` intercepts it
2. **Error Display**: Shows a user-friendly error page with error details
3. **AI Analysis**: User can request AI analysis by clicking the "Ask AI to Fix" button
4. **AI Processing**: Sends error context to Perplexity AI for analysis
5. **Result Display**: Shows AI-generated debugging suggestions

## Views

The package includes two Blade views:

- **`error.blade.php`**: Displays error details and provides option to request AI analysis
- **`fix-result.blade.php`**: Shows AI-generated debugging suggestions

## Service Provider

The `AIErrorHandlerServiceProvider` handles:
- Configuration publishing
- Exception handler binding
- Conditional service registration

## Exception Handler

The `AIExceptionHandler` extends Laravel's default exception handler and:
- Intercepts exceptions when enabled
- Renders custom error views
- Passes error context to views

## Controller

The `AIErrorFixController` manages:
- Error data collection
- Perplexity AI API communication
- Result rendering

## Security Considerations

- **API Key Protection**: Ensure your Perplexity API key is properly secured
- **Error Information**: Be cautious about exposing sensitive error details in production
- **AI Suggestions**: Always review AI suggestions before implementing them

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

### Common Issues

1. **API Key Not Working**: Verify your Perplexity API key is correct
2. **Handler Not Intercepting**: Check if `AI_ERROR_HANDLER_ENABLED` is set to `true`
3. **Configuration Not Published**: Ensure you've published the configuration file

### Debug Mode

Enable Laravel's debug mode to see detailed error information:

```env
APP_DEBUG=true
```

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

### Version 1.0.0
- Initial release
- Basic error handling with Perplexity AI integration
- Configurable error handler
- Custom error views
