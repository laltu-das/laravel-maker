<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Input\InputArgument;

class MakeActionCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {name} {--methods=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new action class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Action';

    /**
     * Execute the console command.
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        parent::handle();
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/action.stub');
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
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Actions';
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the service'],
            ['methods', InputArgument::OPTIONAL, 'The methods you want to add to the service (separated by a comma)'],
        ];
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $replace = [];
        $replace['{{ methods }}'] = $this->option('methods') ? $this->setMethods() : '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    private function setMethods(): string
    {
        $methods = [];
        $methodArguments = explode(',', $this->option('methods'));

        foreach ($methodArguments as $methodArgument) {
            $methods[] = str_replace('{{ method_name }}', $methodArgument, $this->files->get($this->getMethodStub()));
        }

        return implode(PHP_EOL, $methods);
    }

    /**
     * Get the method stub.
     */
    protected function getMethodStub(): string
    {
        return $this->resolveStubPath('/stubs/action-method.stub');
    }
}
