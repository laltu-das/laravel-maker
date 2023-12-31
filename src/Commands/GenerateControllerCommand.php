<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;
use function Laravel\Prompts\confirm;

class GenerateControllerCommand extends ControllerMakeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:controller';

    /**
     * Execute the console command.
     * @throws FileNotFoundException
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

        if ($this->option('inertia')) {
            $this->createInertiaView();
        }

        return false;
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createService(): void
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('generate:service', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createAction(): void
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('generate:action', [
            'name' => "{$name}Action",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createInertiaView(): void
    {
        $nameInput = Str::replace('Controller', '', $this->getNameInput());
        $name = Str::studly($nameInput);
        $fileName = Str::afterLast($name, '/');;

        $this->call('generate:inertia-view', [
            'name' => "{$name}/{$fileName}",
            'model' => Str::lower($name),
            'route' => Str::lower($name),
            '--resource' => true
        ]);
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = ['service', 'S', InputOption::VALUE_NONE, 'Generate a service for the controller'];
        $options[] = ['action', 'A', InputOption::VALUE_NONE, 'Generate a action for the controller'];
        $options[] = ['inertia', 'I', InputOption::VALUE_NONE, 'Generate a inertia for the controller'];

        return $options;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
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
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/'))) ? $customPath : __DIR__ . $stub;
    }



    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name): string
    {

        $modelClass = $this->parseModel($this->option('model'));

        if (! class_exists($modelClass) && confirm("A {$modelClass} model does not exist. Do you want to generate it?", default: true)) {
            $this->call('make:model', ['name' => $modelClass]);
        }

        $replace = [];

        $replace["{{ routePath }}"]  = Str::lower(Str::replace('/', '.', Str::replace('Controller', '', $this->getNameInput())));
        $replace["{{ viewPath }}"]  = Str::replace('Controller', '', $this->getNameInput());

        $replace = $this->buildResourceReplacements($replace, $modelClass);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the model replacement values for Resource classes.
     *
     * @param  array  $replace
     * @param string $modelClass
     * @return array
     */
    protected function buildResourceReplacements(array $replace, string $modelClass): array
    {
        $resourceNamespace = 'App\\Http\\Resources';
        $resourceClass = class_basename($modelClass) . 'Resource';
        $resourceCollectionClass = class_basename($modelClass) . 'ResourceCollection';

        $namespacedResource = $resourceNamespace . '\\' . $resourceClass.';';
        $namespacedResourceCollection = $resourceNamespace . '\\' . $resourceCollectionClass.';';

        return array_merge($replace, [
            '{{ resourceClass }}' => $resourceClass,
            '{{resourceClass}}' => $resourceClass,
            '{{ resourceCollectionClass }}' => $resourceCollectionClass,
            '{{resourceCollectionClass}}' => $resourceCollectionClass,
            '{{ namespacedResources }}' => $namespacedResource,
            '{{namespacedResources}}' => $namespacedResource,
            '{{ namespacedResourceCollection }}' => $namespacedResourceCollection,
            '{{namespacedResourceCollection}}' => $namespacedResourceCollection,
        ]);
    }


}
