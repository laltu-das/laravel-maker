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

    public function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            'service', 'S', InputOption::VALUE_NONE, 'Generate a service for the controller',
            'action', 'A', InputOption::VALUE_NONE, 'Generate a action for the controller',
            'inertia', 'I', InputOption::VALUE_NONE, 'Generate a inertia for the controller'
        ]);
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
        return file_exists($customPath = __DIR__ . $stub) ? $customPath : parent::resolveStubPath($stub);
    }
}
