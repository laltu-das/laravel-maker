<?php

namespace Laltu\LaravelMaker;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laltu\LaravelMaker\Commands\GenerateActionCommand;
use Laltu\LaravelMaker\Commands\GenerateControllerCommand;
use Laltu\LaravelMaker\Commands\GenerateInertiaViewCommand;
use Laltu\LaravelMaker\Commands\GenerateMigrationCommand;
use Laltu\LaravelMaker\Commands\GenerateModelCommand;
use Laltu\LaravelMaker\Commands\GeneratePackageCommand;
use Laltu\LaravelMaker\Commands\GenerateServiceCommand;
use Laltu\LaravelMaker\Commands\GenerateResourceViewFile;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Foundation\Application;
use Laltu\LaravelMaker\Livewire\ModuleApiBuilder;
use Laltu\LaravelMaker\Livewire\ModuleCreate;
use Laltu\LaravelMaker\Livewire\ModuleFormBuilder;
use Laltu\LaravelMaker\Livewire\ModuleValidation;
use Laltu\LaravelMaker\Livewire\SchemaCreate;
use Laltu\LaravelMaker\Livewire\Generator;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\SidePanel;
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
        $this->createDatabase();
        $this->connectToDatabase();

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
                 GenerateControllerCommand::class,
                 GenerateModelCommand::class,
                 GenerateServiceCommand::class,
                 GenerateActionCommand::class,
                 GeneratePackageCommand::class,
                 GenerateInertiaViewCommand::class,
                 GenerateMigrationCommand::class,
                 GenerateResourceViewFile::class,
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

        $this->app->bind(GenerateMigrationCommand::class, function ($app) {
            return new GenerateMigrationCommand(
                $app['migration.creator'],
                $app['composer'],
                $app['files']
            );
        });
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
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        });
    }

    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('laravel-maker.path', 'laravel-maker'),
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
        Livewire::component('laravel-maker.create-schema', SchemaCreate::class);
        Livewire::component('laravel-maker.list-module', ModuleList::class);
        Livewire::component('laravel-maker.create-module', ModuleCreate::class);
        Livewire::component('laravel-maker.module-form-builder', ModuleFormBuilder::class);
        Livewire::component('laravel-maker.module-api-builder', ModuleApiBuilder::class);
        Livewire::component('laravel-maker.module-validation', ModuleValidation::class);
        Livewire::component('laravel-maker.side-panel', SidePanel::class);
    }

    /**
     * Connect to the SQLite database dynamically.
     *
     * @return void
     */
    private function createDatabase(): void
    {
        // Specify the path to the SQLite database file
        $databasePath = storage_path('laravel-maker.sqlite');

        // Check if the file exists
        if (!File::exists($databasePath)) {
            // If not, create an empty SQLite database file
            File::put($databasePath, '');
        }
    }

    /**
     * Connect to the SQLite database dynamically.
     *
     * @return void
     */
    private function connectToDatabase(): void
    {
        try {
            // SQLite database configuration
            $databaseConfig = [
                'driver' => 'sqlite',
                'database' => storage_path('laravel-maker.sqlite'),
                'prefix' => '',
            ];

            // Establish a dynamic database connection
            config(['database.connections.laravel-maker' => $databaseConfig]);

            DB::reconnect('laravel-maker');

            // Optional: Check if the connection is successful
            DB::connection('laravel-maker')->getPdo();
        } catch (\Exception $e) {
            // Handle the exception
            Log::error("Failed to connect to the database: " . $e->getMessage());
            // Optionally rethrow the exception or handle it as per your application's requirement
        }
    }
}
