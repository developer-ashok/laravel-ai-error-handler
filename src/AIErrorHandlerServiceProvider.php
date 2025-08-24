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
            ], 'ai-error-handler-config');
            
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/ai-error-handler'),
            ], 'ai-error-handler-views');
        }

        // Load package views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ai-error-handler');
        
        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if (config('ai-error-handler.enabled')) {
            $this->app->singleton(
                \Illuminate\Contracts\Debug\ExceptionHandler::class,
                AIExceptionHandler::class
            );
        }
    }
}
