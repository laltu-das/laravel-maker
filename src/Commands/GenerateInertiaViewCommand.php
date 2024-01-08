<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class GenerateInertiaViewCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:inertia-view';

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
    protected $type = 'Inertia view';

    /**
     * Execute the console command.
     * @throws FileNotFoundException
     */
    public function handle(): bool
    {
        if ($this->option('resource')) {
            $framework = $this->choice('Select the JavaScript framework (Vue/React):', ['vue', 'react'], 0);

            $this->createResource($framework);
        } else {
            parent::handle();
        }

        return false;
    }


    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath("/stubs/{$this->option('framework')}/{$this->option('framework')}-template.stub");
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
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return array_merge(parent::getArguments(), [
            ['model', InputArgument::REQUIRED, 'The model associated with the view.'],
            ['route', InputArgument::REQUIRED, 'The route name associated with the view.'],
        ]);
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
            ['type', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['framework', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
            ['resource', null, InputOption::VALUE_NONE, 'Indicates if the generated controller should be a resource controller'],
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

        return $this->laravel['path'] . '/../resources/js/Pages/' . str_replace('\\', '/', $name) . '.vue';
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = __DIR__ . $stub) ? $customPath : __DIR__ . $stub;
    }


    /**
     */
    private function createResource($framework): void
    {
        $files = ['Index' => 'index', 'Create' => 'create', 'Edit' => 'edit', 'Show' => 'show',];

        foreach ($files as $option => $suffix) {
            $stubPath = $this->resolveStubPath("/stubs/{$framework}/resource/{$suffix}-template.stub");

            $name = $this->qualifyClass($this->getNameInput() . $option);

            $path = $this->getPath($name);

            if ((!$this->hasOption('force') || !$this->option('force')) && $this->alreadyExists($this->getNameInput())) {
                $this->components->error($this->type . ' already exists.');

                return;
            }

            $this->makeDirectory($path);

            // Read the contents of the stub file
            $stub = file_get_contents($stubPath);

            // Define replacements
            $replace = [
                '{{ routePath }}' => Str::lower(Str::replace('/', '.', Str::replace('Controller', '', $this->getNameInput()))),
                '{{ viewPath }}' => Str::replace('Controller', '', $this->getNameInput()),
                '{{ model }}' => trim($this->argument('model')),
                '{{ modelPlural }}' => Str::of(trim($this->argument('model')))->plural(),
                '{{ modelUcFirstPlural }}' => Str::ucfirst(Str::of(trim($this->argument('model')))->plural()),
                '{{ route }}' => trim($this->argument('route')),
            ];

            // Perform replacements in the stub
            $stub = str_replace(array_keys($replace), array_values($replace), $stub);

            // Write the file with the replaced contents
            $this->files->put($path, $stub);

            $this->components->info(sprintf('%s [%s] created successfully.', $this->type, $path));
        }


    }
}
