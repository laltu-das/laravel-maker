<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeModelCommand extends ModelMakeCommand
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        parent::handle();

        if ($this->option('all')) {
            $this->input->setOption('service', true);
            $this->input->setOption('action', true);
        }

        if ($this->option('service')) {
            $this->createService();
        }

        if ($this->option('action')) {
            $this->createAction();
        }
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createService(): void
    {
        $name = Str::studly($this->getNameInput());

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
        $name = Str::studly($this->getNameInput());

        $this->call('make:action', [
            'name' => "{$name}Action",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();
        $options[] = ['service', 'S', InputOption::VALUE_NONE, 'Generate a service for the model'];
        $options[] = ['action', 'A', InputOption::VALUE_NONE, 'Generate a service for the model'];

        return $options;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('pivot')) {
            return $this->resolveStubPath('/stubs/model.pivot.stub');
        }

        if ($this->option('morph-pivot')) {
            return $this->resolveStubPath('/stubs/model.morph-pivot.stub');
        }

        return $this->resolveStubPath('/stubs/model.stub');
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
}
