<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;
use InvalidArgumentException;

#[AsCommand(name: 'make:action')]
class MakeActionCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new action class';


    /**
     * Retrieves the stub path for the given action.
     *
     * @return string The stub path.
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/action.stub');
    }

    /**
     * Retrieves the method stub path.
     *
     * @return string The path to the method stub.
     */
    protected function getMethodStub(): string
    {
        return $this->resolveStubPath('/stubs/action.method.stub');
    }

    /**
     * Resolves the path for a stub file.
     *
     * @param string $stub The path to the stub file.
     * @return string The resolved path for the stub file.
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3) . $stub) ? $customPath : __DIR__ . $stub;
    }

    /**
     * Get the default namespace for the given root namespace.
     *
     * @param string $rootNamespace The root namespace.
     * @return string The default namespace.
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\Actions';
    }


    /**
     * Get the options for the command.
     *
     * @return array An array of options for the command.
     */
    public function getOptions(): array
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model associated with the view.'],
            ['methods', null, InputOption::VALUE_OPTIONAL, 'The methods you want to add to the service (separated by a comma)'],
            ['force', null, InputOption::VALUE_OPTIONAL, 'Create the class even if the model already exists'],
        ];
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        if ($methods = $this->option('methods')) {
            $this->addMethodsToClass($stub, $methods);
        }

        $model = $this->option('model');

        return $model ? $this->replaceModel($stub, $model) : $stub;
    }

    /**
     * Replace the model for the given stub.
     *
     * @param string $stub
     * @param string $model
     * @return string
     */
    protected function replaceModel(string $stub, string $model): string
    {
        $modelClass = $this->parseModel($model);

        $replace = [
            '{{ namespacedModel }}' => $modelClass,
            '{{ model }}' => class_basename($modelClass),
            '{{ modelVariable }}' => lcfirst(class_basename($modelClass)),
        ];

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    /**
     * Get the fully qualified model class name.
     *
     * @param string $model
     * @return string
     *
     * @throws InvalidArgumentException
     */
    protected function parseModel(string $model): string
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Sets the methods based on the provided method arguments.
     *
     * @return string The concatenated and modified method stubs.
     * @throws FileNotFoundException
     */
    private function addMethodsToClass(string $class, string $methods): string
    {
        $methods = [];
        $methodArguments = explode(',', $this->option('methods'));

        foreach ($methodArguments as $methodArgument) {
            $methods[] = str_replace('{{ method_name }}', $methodArgument, $this->files->get($this->getMethodStub()));
        }

        return implode(PHP_EOL, $methods);
    }
}
