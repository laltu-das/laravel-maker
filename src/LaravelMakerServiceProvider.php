<?php

namespace Laltu\LaravelMaker;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laltu\LaravelMaker\Commands\MakeActionCommand;
use Laltu\LaravelMaker\Commands\MakeControllerCommand;
use Laltu\LaravelMaker\Commands\MakeFactoryCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaFormCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaPageCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaTableCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaViewCommand;
use Laltu\LaravelMaker\Commands\MakeMigrationCommand;
use Laltu\LaravelMaker\Commands\MakeModelCommand;
use Laltu\LaravelMaker\Commands\MakeResourceCommand;
use Laltu\LaravelMaker\Commands\MakeServiceCommand;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Foundation\Application;
use Laltu\LaravelMaker\Commands\MakePackageCommand;
use Laltu\LaravelMaker\Livewire\ModuleApiBuilder;
use Laltu\LaravelMaker\Livewire\ModuleForm;
use Laltu\LaravelMaker\Livewire\ModuleFormBuilder;
use Laltu\LaravelMaker\Livewire\ModuleValidation;
use Laltu\LaravelMaker\Livewire\SchemaForm;
use Laltu\LaravelMaker\Livewire\Generator;
use Laltu\LaravelMaker\Livewire\ModuleList;
use Laltu\LaravelMaker\Livewire\Dashboard;
use Laltu\LaravelMaker\Livewire\SchemaList;
use Laltu\LaravelMaker\Livewire\Setting;
use Laltu\LaravelMaker\Livewire\SidePanel;
use Livewire\Livewire;

class LaravelMakerServiceProvider extends ServiceProvider
{

    /**
     * Boot method for the package.
     */
    public function boot(): void
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-maker');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-maker');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->registerDatabase();

        $this->registerAuthorization();
        $this->registerRoutes();
        $this->registerResources();
        $this->registerLivewireComponents();


        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/laravel-maker.php' => config_path('laravel-maker.php'),
            ], 'config');

            // Publishing assets.
            $this->publishes([
                __DIR__.'/../dist' => public_path('vendor/laravel-maker'),
            ], ['assets', 'laravel-assets']);

        }

        // Registering package commands.
        $this->commands([
            MakeServiceCommand::class,
            MakeActionCommand::class,
            MakeControllerCommand::class,
            MakeModelCommand::class,
            MakeMigrationCommand::class,
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
     * Register the package's services and configurations.
     *
     * @return void
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/laravel-maker.php', 'laravel-maker');

        $this->app->bind(MakeMigrationCommand::class, function ($app) {
            return new MakeMigrationCommand(
                $app['migration.creator'],
                $app['composer'],
            );
        });

        // Register the main class to use with the facade
        $this->app->singleton('laravel-maker', function () {
            return new \Laltu\LaravelMaker\Facades\LaravelMaker();
        });
    }

    /**
     * Register the authorization permissions.
     *
     * @return void
     */
    protected function registerAuthorization(): void
    {
        $this->callAfterResolving(Gate::class, function (Gate $gate, Application $app) {
            $gate->define('viewPulse', fn ($user = null) => $app->environment('local'));
        });
    }

    /**
     * Register the package resources.
     *
     * This method is responsible for loading the package's views from the specified directory.
     *
     * @return void
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-maker');
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
        });
    }

    /**
     * Retrieves configuration for route.
     *
     * This method retrieves the configuration for a route used by the Laravel Maker package.
     * It returns an array containing the prefix and middleware specified in the package's configuration.
     *
     * @return array The route configuration.
     */
    protected function routeConfiguration(): array
    {
        return [
            'prefix' => config('laravel-maker.path', 'laravel-maker'),
            'middleware' => config('laravel-maker.middleware'),
        ];
    }

    /**
     * Registers Livewire components.
     *
     * This method registers Livewire components using the provided aliases and classes.
     *
     * @return void
     */
    protected function registerLivewireComponents(): void
    {
        $components = [
            'laravel-maker.dashboard' => Dashboard::class,
            'laravel-maker.schema' => SchemaList::class,
            'laravel-maker.generator' => Generator::class,
            'laravel-maker.setting' => Setting::class,
            'laravel-maker.create-schema' => SchemaForm::class,
            'laravel-maker.list-module' => ModuleList::class,
            'laravel-maker.create-module' => ModuleForm::class,
            'laravel-maker.module-form-builder' => ModuleFormBuilder::class,
            'laravel-maker.module-api-builder' => ModuleApiBuilder::class,
            'laravel-maker.module-validation' => ModuleValidation::class,
            'laravel-maker.side-panel' => SidePanel::class,
        ];

        foreach ($components as $alias => $class) {
            $this->registerComponent($alias, $class);
        }
    }

    /**
     * Register a Livewire component.
     *
     * @param string $alias The alias for the component.
     * @param string $class The fully qualified class name of the component.
     *
     * @return void
     */
    private function registerComponent(string $alias, string $class): void
    {
        Livewire::component($alias, $class);
    }


    protected function registerDatabase(): void
    {
        $sqlitePath = database_path('laravel_maker.sqlite');

        if (!file_exists($sqlitePath)) {
            try {
                touch($sqlitePath); // Creates the file if it doesn't exist.
            } catch (\Exception $e) {
                // Handle exception if the database file could not be created
                logger()->error("Unable to create SQLite database for Laravel Maker: " . $e->getMessage());
            }
        }

        // Ensure the configuration is only set if the file exists or was successfully created
        if (file_exists($sqlitePath)) {
            config()->set('database.connections.laravel_maker', [
                'driver' => 'sqlite',
                'database' => $sqlitePath,
                'prefix' => '',
            ]);
        }
    }

}
