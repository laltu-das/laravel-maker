<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class MakeInertiaViewCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:inertia-view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Vue template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Vue template';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('resource-index')) {
            return $this->resolveStubPath('/stubs/vue/resource/index-vue-template.stub');
        }

        if ($this->option('resource-create')) {
            return $this->resolveStubPath('/stubs/vue/resource/create-vue-template.stub');
        }

        if ($this->option('resource-edit')) {
            return $this->resolveStubPath('/stubs/vue/resource/edit-vue-template.stub');
        }

        if ($this->option('resource-show')) {
            return $this->resolveStubPath('/stubs/vue/resource/show-vue-template.stub');
        }

        return $this->resolveStubPath('/stubs/vue/vue-template.stub');
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     * @return bool
     */
    protected function alreadyExists($rawName): bool
    {
        $name = class_basename(str_replace('\\', '/', $rawName));

        $path = "{$this->laravel['path']}/../resources/js/Pages/{$name}.vue";

        return file_exists($path);
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceNamespace(&$stub, $name): static
    {
        $name = class_basename(str_replace('\\', '/', $name));

        $stub = str_replace('{Component}', $name, $stub);

        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
            ['react', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['vue', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['resource', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['-index', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['-create', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['-edit', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['-show', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
        ];
    }


    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/../resources/js/Pages/'.str_replace('\\', '/', $name).'.vue';
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = __DIR__ . $stub) ? $customPath : __DIR__ . $stub;
    }


    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param string $name
     * @return string
     * @throws FileNotFoundException
     */
    protected function buildClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        if ($this->option('creatable')) {
            $replace['abort(404);'] = '//';
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        $replace["{{ routePath }}"]  = Str::lower(Str::replace('/', '.', Str::replace('Controller', '', $this->getNameInput())));
        $replace["{{ viewPath }}"]  = Str::replace('Controller', '', $this->getNameInput());

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }
}
