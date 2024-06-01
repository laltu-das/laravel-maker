<?php

namespace Laltu\LaravelMaker\Commands;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeControllerCommand extends ControllerMakeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:controller';

    /**
     * Handle the command.
     *
     * @return bool
     * @throws FileNotFoundException
     * @throws Exception
     */
    public function handle(): bool
    {
        parent::handle();

        if ($this->option('service')) {
            $this->createService();
        }

        if ($this->option('action')) {
            $this->createAction();
        }

        return false;
    }

    /**
     * Creates a service for the specified controller name.
     *
     * @return void
     */
    protected function createService(): void
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('make:service', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Creates a controller action.
     *
     * This method creates a new controller action by calling the `make:action` command with the provided options.
     *
     * @return void
     * @throws Exception If the command execution fails or encounters an error.
     * @see make:action
     *
     */
    protected function createAction(): void
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('make:action', [
            'name' => "{$name}Action",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Get the options for the command.
     *
     * @return array An array of options for the command.
     */
    public function getOptions(): array
    {
        // Merge the parent options with the additional options
        return array_merge(parent::getOptions(), [
            ['service', null, InputOption::VALUE_NONE, 'Generates a service class for the controller'],
            ['action', null, InputOption::VALUE_NONE, 'Generates an action method for the controller'],
            ['inertia', null, InputOption::VALUE_NONE, 'Generates a controller with basic Inertia.js support'],
            ['with-inertia-resource', null, InputOption::VALUE_NONE, 'Generates a controller with resources(collection) for Inertia.js'],
        ]);
    }

    /**
     * Create an Inertia view for the controller.
     *
     * This method creates an Inertia view by calling the 'make:inertia-view'
     * console command with the appropriate arguments and options.
     *
     * @return void
     */
    protected function createInertiaResource(): void
    {
        $modelClass = Str::studly($this->option('model'));

        $this->call('make:resource', [
            'name' => "{$modelClass}Resource",
        ]);
    }

    /**
     * Create an Inertia view for the controller.
     *
     * This method creates an Inertia view by calling the 'make:inertia-view'
     * console command with the appropriate arguments and options.
     *
     * @return void
     */
    protected function createInertiaReact(): void
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);
        $fileName = Str::afterLast($name, '/');

        $this->call('make:inertia-view', [
            'name' => "{$name}/{$fileName}",
            '--model' => Str::lower($name),
            'route' => Str::lower($name),
            '--resource' => true
        ]);
    }

    /**
     * Retrieve the stub to be used for generating the controller.
     *
     * @return string The path of the stub file to be used.
     */
    protected function getStub(): string
    {
        if ($this->option('inertia')) {
            return $this->resolveStubPath('/stubs/controller.model.inertia.stub');
        } else {
            return parent::getStub();
        }
    }

    /**
     * Resolves the path to a stub file.
     *
     * @param string $stub The relative path to the stub file
     * @return string The resolved path to the stub file
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3) . $stub) ? $customPath : __DIR__ . $stub;
    }


    /**
     * Build a class for the given name.
     *
     * @param string $name The name of the class to build.
     * @return string The built class.
     */
    protected function buildClass($name)
    {
        $rootNamespace = $this->rootNamespace();
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('api')) {
            $replace = $this->buildResourceReplacements($replace);
        }

        if ($this->option('creatable')) {
            $replace['abort(404);'] = '//';
        }

        $baseControllerExists = file_exists($this->getPath("{$rootNamespace}Http\Controllers\Controller"));

        if ($baseControllerExists) {
            $replace["use {$controllerNamespace}\Controller;\n"] = '';
        } else {
            $replace[' extends Controller'] = '';
            $replace["use {$rootNamespace}Http\Controllers\Controller;\n"] = '';
        }

        $class = str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );

        return $class;
    }

    /**
     * Build the resource replacements an array.
     *
     * @param array $replace The array of replacements.
     *
     * @return array The updated array of replacements.
     */
    protected function buildResourceReplacements(array $replace): array
    {
        $modelClass = $this->parseModel($this->option('model'));

        $resourceNamespace = 'App\\Http\\Resources';
        $resourceClass = class_basename($modelClass) . 'Resource';
        $resourceCollectionClass = class_basename($modelClass) . 'ResourceCollection';

        if (!class_exists($resourceClass) && confirm("A {$resourceClass} resource does not exist. Do you want to generate it?", default: true)) {
            $this->call('make:resource', ['name' => $resourceClass]);
        }

        $namespacedResource = $resourceNamespace . '\\' . $resourceClass . ';';

        return array_merge($replace, [
            '{{ resourceClass }}' => $resourceClass,
            '{{resourceClass}}' => $resourceClass,
            '{{ namespacedResource }}' => $namespacedResource,
            '{{namespacedResource}}' => $namespacedResource,
        ]);
    }

}
