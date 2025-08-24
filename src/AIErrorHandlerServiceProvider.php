<?php

namespace LaravelAIErrorHandler;

use Illuminate\Support\ServiceProvider;

class AIErrorHandlerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/ai-error-handler.php', 'ai-error-handler');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/ai-error-handler.php' => config_path('ai-error-handler.php'),
            ], 'config');
        }

        if (config('ai-error-handler.enabled')) {
            $this->app->singleton(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                AIExceptionHandler::class
            );
        }
    }
}
