<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Laltu\LaravelMaker\Support\VueTableBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

#[AsCommand(name: 'make:inertia-table', description: 'Create a new inertia table view')]
class MakeInertiaTableCommand extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:inertia-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new inertia table view';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource File create';


    /**
     * Retrieves the path to the stub file for creating a form template using the Inertia framework.
     *
     * @return string The path to the form template stub file
     */
    protected function getStub(): string
    {
        $framework = $this->option('stack');

        return $this->resolveStubPath("/stubs/inertia/$framework/table-template.stub");
    }

    /**
     * Resolves the path to a stub file.
     *
     * @param string $stub The relative path to the stub file
     * @return string The resolved path to the stub file
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3) . $stub) ? $customPath : __DIR__ . $stub;
    }

    /**
     * Get the destination class path.
     *
     * @param string $name
     * @return string
     */
    protected function getPath($name): string
    {
        $framework = $this->option('stack');

        $extension = $framework == 'vue' ? '.vue' : '.js';

        return $this->laravel['path.resources'] . '/js/Pages/' . str_replace('\\', '/', $name) . $extension;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Merge the parent options with the additional options
        return [
            ['stack', 's', InputOption::VALUE_REQUIRED, 'The framework associated with the view.'],
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model associated with the view.'],
            ['route', 'r', InputOption::VALUE_OPTIONAL, 'The route name associated with the view.'],
            ['fields', null, InputOption::VALUE_OPTIONAL, 'Additional fields for the view in format: "name:name,type:text,default:null,col:12; name:email,type:email,col:6; name:city,type:select,col:6,options:blue|yellow|green|red|white"'],
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }


    protected function replaceClass($stub, $name): string
    {
        // Generate component parts using VueFormBuilder.
        $model = $this->option('model');
        $fields = $this->option('fields') ? $this->parseFields($this->option('fields')) : [];
        $routeName = $this->option('route');
        $pageTitle = 'Example Page Title';

        $vueFormBuilder = new VueTableBuilder($model, $fields, $routeName);

        $componentParts = [
            'modelNameCamel' => Str::camel($vueFormBuilder->model),
            'modelPlural' => Str::plural($vueFormBuilder->model), // Use Str::plural to generate plural form
            'pageTitle' => $pageTitle,
            'routeName' => $vueFormBuilder->routeName,
            'tableColumns' => $vueFormBuilder->generateTableColumns(),
            'model' => $vueFormBuilder->model,
            'tableData' => $vueFormBuilder->generateTableData(),
            'scriptSetupContent' => $vueFormBuilder->generateScriptSetupContent(),
            'dynamicImports' => $vueFormBuilder->getDynamicImports(),
        ];

        // Prepare replacements for both placeholder variations
        $replace = collect($componentParts)->flatMap(function ($value, $key) {
            return ["{{ $key }}" => $value, "{{$key}}" => $value];
        })->toArray();

        return str_replace(array_keys($replace), array_values($replace), $stub);
    }

    /**
     * Perform actions after the user was prompted for missing arguments.
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $fields = [];

        if (!$input->getOption('stack')) {
            $vendorName = select('Framework name:', [
                'vue' => 'Vue Js',
                'react' => 'React Js',
            ], 'vue');
            $input->setOption('stack', $vendorName);
        }

        if (!$input->getOption('model')) {
            $models = $this->getAllModels();

            $vendorName = select('Model name:', $models);
            $input->setOption('model', $vendorName);
        }

        if (!$input->getOption('route')) {
            $routes = $this->getAllRoutes();

            $keywords = select('Route name:', $routes);
            $input->setOption('route', $keywords);
        }

        if (!$input->getOption('fields')) {
            $fields[] = $this->askForFields();

            // Set the joined fields as a single option value
            $input->setOption('fields', join(';', $fields));
        }


        // Loop until the user chooses not to add more fields.
        while (true) {
            // Ask for more fields.
            if ($this->confirmFieldAddition() === 'yes') {
                $fieldDetails = $this->askForFields();
                $fields[] = $fieldDetails; // Add the details to the fields array.
            } else {
                break; // Exit the loop if the user chooses "no".
            }
        }
    }

    protected function getAllModels(): array
    {
        $models = [];
        $finder = new Finder();
        $finder->files()->in(app_path('Models'))->name('*.php');

        foreach ($finder as $file) {
            $className = str_replace(['/', '.php'], ['\\', ''], $file->getRelativePathname());
            $models[] = 'App\\Models\\' . Str::replaceLast('.php', '', $className);
        }

        return $models;
    }

    protected function getAllRoutes(): array
    {
        return collect(Route::getRoutes())->map->getName()->filter()->sort()->toArray();
    }

    protected function askForFields(): string
    {
        $htmlInputTypes = [
            'text', 'number', 'date', 'datetime-local', 'email', 'password', 'checkbox', 'radio', 'file',
            'hidden', 'image', 'month', 'range', 'search', 'tel', 'time', 'url', 'color', 'button', 'submit', 'reset',
        ];

        $cols = array_map(function ($i) {
            return "col-$i";
        }, range(1, 12));

        $name = text('Enter field name (or leave empty to finish):');
        $type = select('Select field type:', $htmlInputTypes);
        $default = text('Field default value:');
        $col = select('Select field column:', $cols);

        return "name:$name,type:$type,default:$default,col:$col;";
    }

    protected function confirmFieldAddition(): int|string
    {
        return select('Would you like to add more fields?', [
            'yes' => 'Yes',
            'no' => 'No',
        ], 'yes');
    }
}