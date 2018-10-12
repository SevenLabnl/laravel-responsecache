<?php

namespace SevenLab\ResponseCache;

use Illuminate\Support\ServiceProvider;
use SevenLab\ResponseCache\Commands\Clear;

class ResponseCacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/responsecache.php' => config_path('responsecache.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                Clear::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/responsecache.php', 'responsecache');
    }
}
