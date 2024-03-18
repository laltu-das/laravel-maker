<?php

namespace Laltu\LaravelMaker\Commands\Core;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class InertiaViewBuilderCommand extends Command
{
    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $files;

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type;

    /**
     * Create a new controller creator command instance.
     *
     * @param Filesystem $files
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        $name = $this->getNameInput();

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does, we don't want
        // to create the class and overwrite the user's code. So, we will bail out so the
        // code is untouched. Otherwise, we will continue generating this class' files.
        if ((!$this->hasOption('force') || !$this->option('force')) && $this->alreadyExists($this->getNameInput())) {
            $this->components->error($this->type . ' already exists.');

            return false;
        }

        // Next, we will generate the path to the location where this class' file should get
        // written. Then, we will build the class and make the proper replacements on the
        // stub files so that it gets the correctly formatted namespace and class name.
        $this->makeDirectory($path);

        $this->files->put($path, $this->sortImports($this->buildStub($name)));

        $info = $this->type;

        if (windows_os()) {
            $path = str_replace('/', '\\', $path);
        }

        $this->components->info(sprintf('%s [%s] created successfully.', $info, $path));
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput(): string
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath(string $name): string
    {
        $framework = $this->getFrameworkInput();

        $extension = $framework == 'vue' ? '.vue' : '.js';

        return $this->laravel['path.resources'] . '/js/Pages/' . str_replace('\\', '/', $name) . $extension;
    }

    /**
     * Get the desired framework from the input.
     *
     * @return string
     */
    protected function getFrameworkInput(): string
    {
        return trim($this->argument('framework') ?? 'vue');
    }

    /**
     * Determine if the class already exists.
     *
     * @param string $rawName
     * @return bool
     */
    protected function alreadyExists(string $rawName): bool
    {
        return $this->files->exists($this->getPath($rawName));
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param string $path
     * @return string
     */
    protected function makeDirectory(string $path): string
    {
        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }

        return $path;
    }




    /**
     * Alphabetically sorts the imports for the given stub.
     *
     * @param string $stub
     * @return string
     */
    protected function sortImports(string $stub): string
    {
        if (preg_match('/(?P<imports>(?:^use [^;{]+;$\n?)+)/m', $stub, $match)) {
            $imports = explode("\n", trim($match['imports']));
            sort($imports);
            return str_replace(trim($match['imports']), implode("\n", $imports), $stub);
        }
        return $stub;
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildStub(string $name): string
    {
        // Get the original stub content
        $stub = $this->files->get($this->getStub());

        // Replace the class name in the indented stub content
        return $this->replaceClass($stub, $name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    abstract protected function getStub(): string;

    /**
     * Replace the class name for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return string
     */
    abstract protected function replaceClass(string $stub, string $name): string;

    /**
     * Parses the 'fields' string into an array.
     */
    public function parseFields($fieldsString): array
    {
        $fields = explode(';', $fieldsString);
        return collect($fields)
            ->filter(function ($field) {
                return !empty($field);
            })
            ->map(function ($field) {
                $fieldParts = explode(',', $field);
                $fieldArray = [];
                foreach ($fieldParts as $part) {
                    list($key, $value) = explode(':', $part, 2) + [null, null];
                    if ($key === 'options') {
                        $value = explode('|', $value);
                    }
                    $fieldArray[trim($key)] = $value;
                }
                return $fieldArray;
            })
            ->toArray();
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the ' . strtolower($this->type)],
            ['framework', InputArgument::REQUIRED, 'The name of the (react, vue)' . strtolower($this->type)],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['type', null, InputOption::VALUE_REQUIRED, 'Manually specify the controller stub file to use'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }
}
