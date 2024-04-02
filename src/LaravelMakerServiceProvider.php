<?php

namespace Laltu\LaravelMaker;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Laltu\LaravelMaker\Commands\MakeActionCommand;
use Laltu\LaravelMaker\Commands\MakeControllerCommand;
use Laltu\LaravelMaker\Commands\MakeFactoryCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaFormCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaPageCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaTableCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaViewCommand;
use Laltu\LaravelMaker\Commands\MakeMigrationCommand;
use Laltu\LaravelMaker\Commands\MakeModelCommand;
use Laltu\LaravelMaker\Commands\MakePackageCommand;
use Laltu\LaravelMaker\Commands\MakeResourceCommand;
use Laltu\LaravelMaker\Commands\MakeServiceCommand;

class LaravelMakerServiceProvider extends ServiceProvider
{

    /**
     * Boot method for the package.
     */
    public function boot(): void
    {

        Inertia::setRootView('laravel-maker::layout');
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-maker');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-maker');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerRoutes();
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-maker.php' => config_path('laravel-maker.php'),
            ], 'config');

            // Publishing assets.
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/laravel-maker'),
            ], ['assets', 'laravel-assets']);
        }


        // Registering package commands.
        $this->commands([
            MakeServiceCommand::class,
            MakeActionCommand::class,
            MakeControllerCommand::class,
            MakeMigrationCommand::class,
            MakeModelCommand::class,
            MakeResourceCommand::class,
            MakeFactoryCommand::class,

            MakeInertiaViewCommand::class,
            MakeInertiaPageCommand::class,
            MakeInertiaFormCommand::class,
            MakeInertiaTableCommand::class,

            MakePackageCommand::class,
        ]);
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::name('laravel-maker.')
            ->prefix(config('laravel-maker.path', 'laravel-maker'))
            ->middleware(config('laravel-maker.middleware'))
            ->group(function () {
                $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
            });
    }


    /**
     * Register the package resources.
     *
     * This method is responsible for loading the package's Pages from the specified directory.
     *
     * @return void
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-maker');
    }

    /**
     * Register the package's services and configurations.
     *
     * @return void
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-maker.php', 'laravel-maker');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-maker', function () {
            return new \Laltu\LaravelMaker\Facades\LaravelMaker();
        });
    }

}
