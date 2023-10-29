<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Foundation\Console\ViewMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeViewCommand extends ViewMakeCommand
{
    /**
     * Execute the console command.
     */
    public function handle(): bool
    {
        parent::handle();

        if ($this->option('resource')) {
            $this->createResource();
        }

        return false;
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createResource(): void
    {
        $nameInput = Str::replace('', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('make:view', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createInertiaVue(): void
    {
        $nameInput = Str::replace('', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('make:view --vue', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createInertiaReact(): void
    {
        $nameInput = Str::replace('', '', $this->getNameInput());
        $name = Str::studly($nameInput);

        $this->call('make:view --inertia-react', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = ['resource', null, InputOption::VALUE_NONE, 'Generate a resource view'];
        $options[] = ['vue', null, InputOption::VALUE_NONE, 'Generate a inertia vue js file'];
        $options[] = ['react', null, InputOption::VALUE_NONE, 'Generate a inertia react js file'];

        return $options;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        $stub = parent::getStub();

        if ($this->option('vue')) {
            $stub = '/stubs/vue/vue-template.stub';
        } elseif ($this->option('react')) {
            $stub = '/stubs/react/react-template.stub';
        }

        return $this->resolveStubPath($stub);
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }


    /**
     * Get the destination view path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name)
    {
        $path = parent::getPath($name);

        return $this->viewPath(
            $this->getNameInput() . '.' . $this->option('extension'),
        );
    }
}
