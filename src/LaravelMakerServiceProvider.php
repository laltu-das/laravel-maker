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
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Foundation\Application;
use Laltu\LaravelMaker\Livewire\CreateModule;
use Laltu\LaravelMaker\Livewire\CreateSchema;
use Laltu\LaravelMaker\Livewire\Generator;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\FormBuilder;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\Servers;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\ViewModule;
use Livewire\Livewire;

use Illuminate\Support\Facades\DB;

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
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-maker');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-maker');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerAuthorization();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerLivewireComponents();
//        $this->connectToDatabase();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-maker.php' => config_path('laravel-maker.php'),
            ], 'config');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/laravel-maker'),
            ], ['assets', 'laravel-assets']);

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

    /**
     * Register the application services.
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

    /**
     * Register the package authorization.
     */
    protected function registerAuthorization(): void
    {
        $this->callAfterResolving(Gate::class, function (Gate $gate, Application $app) {
            $gate->define('viewPulse', fn ($user = null) => $app->environment('local'));
        });
    }

    /**
     * Register the package's resources.
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-maker');
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
     * Register Livewire components.
     *
     * @return void
     */
    protected function registerLivewireComponents(): void
    {
        Livewire::component('laravel-maker.dashboard', Dashboard::class);
        Livewire::component('laravel-maker.schema', SchemaList::class);
        Livewire::component('laravel-maker.generator', Generator::class);
        Livewire::component('laravel-maker.setting', Setting::class);
        Livewire::component('laravel-maker.create-schema', CreateSchema::class);
        Livewire::component('laravel-maker.list-module', ModuleList::class);
        Livewire::component('laravel-maker.create-module', CreateModule::class);
        Livewire::component('laravel-maker.view-module', ViewModule::class);
        Livewire::component('laravel-maker.form-builder', FormBuilder::class);
        Livewire::component('laravel-maker.servers', Servers::class);
    }

    /**
     * Connect to the SQLite database dynamically.
     *
     * @return void
     */
    private function connectToDatabase(): void
    {
        // SQLite database configuration
        $databaseConfig = [
            'driver' => 'sqlite',
            'database' => database_path('your_database.sqlite'), // Path to your SQLite database file
            'prefix' => '',
        ];

        // Establish a dynamic database connection
        config(['database.connections.dynamic' => $databaseConfig]);
        DB::purge('dynamic');
        DB::reconnect('dynamic');
    }
}
