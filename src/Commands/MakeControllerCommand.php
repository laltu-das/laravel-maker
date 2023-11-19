<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeControllerCommand extends ControllerMakeCommand
{
    /**
     * Execute the console command.
     */
    public function handle()
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

        $this->call('make:service', [
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

        $this->call('make:action', [
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

        $this->call('make:inertia-view', [
            'name' => "{$name}/{$fileName}",
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
    protected function buildClass($name)
    {
        $replace = [];

        $replace["{{ routePath }}"]  = Str::lower(Str::replace('/', '.', Str::replace('Controller', '', $this->getNameInput())));
        $replace["{{ viewPath }}"]  = Str::replace('Controller', '', $this->getNameInput());

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

}
