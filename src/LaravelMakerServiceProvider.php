<?php

namespace Laltu\LaravelMaker;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Laltu\LaravelMaker\Commands\MakeActionCommand;
use Laltu\LaravelMaker\Commands\MakeControllerCommand;
use Laltu\LaravelMaker\Commands\MakeInertiaViewCommand;
use Laltu\LaravelMaker\Commands\MakeModelCommand;
use Laltu\LaravelMaker\Commands\MakePackageCommand;
use Laltu\LaravelMaker\Commands\MakeServiceCommand;
use Laltu\LaravelMaker\Commands\ResourceFileCreate;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Foundation\Application;
use Laltu\LaravelMaker\Livewire\CreatePost;
use Laltu\LaravelMaker\Livewire\Servers;
use Livewire\Livewire;
use Livewire\LivewireManager;


class LaravelMakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     * @throws BindingResolutionException
     */
    public function boot(): void
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'laravel-maker');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-maker');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

//        $this->registerAuthorization();
        $this->registerRoutes();
        $this->registerBladeComponents();
        $this->registerComponents();
//        $this->registerLivewireComponents();
        $this->registerResources();

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
     * Register the package routes.
     */
    protected function registerRoutes(): void
    {
        $this->callAfterResolving('router', function (Router $router, Application $app) {
            $router->group([
                'prefix' => config('laravel-maker.prefix', 'laravel-maker'),
                'middleware' => config('laravel-maker.middleware'),
            ], function (Router $router) {
                $router->get('/', function (LaravelMaker $laravelMaker, ViewFactory $view) {
                    return view('laravel-maker::dashboard');
                })->name('laravel-maker');
            });
        });
    }

    /**
     * Register Blade components.
     *
     * @return void
     */
    protected function registerBladeComponents(): void
    {
        $this->app->afterResolving('blade.compiler', function (BladeCompiler $blade) {
            $blade->anonymousComponentPath(__DIR__.'/../resources/views/components', 'laravel-maker');
        });
    }

    /**
     * Register the package's components.
     */
    protected function registerComponents(): void
    {
        Livewire::component('laravel-maker.create-post', CreatePost::class);
        Livewire::component('laravel-maker.servers', Servers::class);
    }


    /**
     * Register Livewire components.
     *
     * @return void
     * @throws BindingResolutionException
     */
    protected function registerLivewireComponents(): void
    {
        $this->callAfterResolving('livewire', function (LivewireManager $livewire, Application $app) {
            $config = $app->make('config');

            $livewire->addPersistentMiddleware($config->get('laravel-maker.middleware', []));

            $components = [
                'create-post' => Livewire\CreatePost::class,
                'cache' => Livewire\Cache::class,
                'servers' => Livewire\Servers::class,
                // Add other components here...
            ];

            foreach ($components as $alias => $class) {
                $livewire->component("laravel-maker.$alias", $class);
            }
        });
    }
}
