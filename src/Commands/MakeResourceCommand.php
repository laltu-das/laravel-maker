<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeResourceCommand extends ResourceMakeCommand
{
    /**
     * Execute the console command.
     */
    public function handle(): bool
    {
        parent::handle();

        if ($this->option('model')) {
            $this->createResource();
        }

        return false;
    }

    public function getOptions(): array
    {
        return array_merge(parent::getOptions(), [
            'model', 'm', InputOption::VALUE_NONE, 'Generate a resource view'
        ]);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('model')) {
            return $this->collection()
                ? $this->resolveStubPath('/stubs/resource-collection.model.stub')
                : $this->resolveStubPath('/stubs/resource.model.stub');
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
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }
}
