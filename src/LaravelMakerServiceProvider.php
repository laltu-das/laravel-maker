<?php

namespace Laltu\LaravelMaker;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laltu\LaravelMaker\Commands\MakeActionCommand;
use Laltu\LaravelMaker\Commands\MakeControllerCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaViewCommand;
use Laltu\LaravelMaker\Commands\MakeModelCommand;
use Laltu\LaravelMaker\Commands\MakePackageCommand;
use Laltu\LaravelMaker\Commands\MakeServiceCommand;
use Laltu\LaravelMaker\Commands\ResourceFileCreate;

class LaravelMakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        /*
         * Optional methods to load your package assets
         */
//        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-maker');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-maker');
//        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('laravel-maker.php'),
            ], 'config');

            // Publishing assets.
//            $this->publishes([
//                __DIR__.'/../public' => public_path('vendor/laravel-maker'),
//            ], ['assets', 'laravel-assets']);

            // Registering package commands.
             $this->commands([
                 MakeControllerCommand::class,
                 MakeModelCommand::class,
                 MakeServiceCommand::class,
                 MakeActionCommand::class,
                 MakePackageCommand::class,
                 MakeInertiaViewCommand::class,
                 ResourceFileCreate::class,
             ]);
        }
    }

    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('laravel-maker.prefix', 'laravel-maker'),
            'middleware' => config('laravel-maker.middleware'),
        ];
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'laravel-maker');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-maker', function () {
            return new LaravelMaker;
        });
    }
}
