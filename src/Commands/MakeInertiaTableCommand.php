<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Support\Str;
use Laltu\LaravelMaker\Commands\Core\InertiaViewBuilderCommand;
use Laltu\LaravelMaker\Support\VueTableBuilder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:inertia-table', description: 'Create a new inertia table view')]
class MakeInertiaTableCommand extends InertiaViewBuilderCommand
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
    protected string $type = 'Resource File create';


    /**
     * Retrieves the path to the stub file for creating a form template using the Inertia framework.
     *
     * @return string The path to the form template stub file
     */
    protected function getStub(): string
    {
        $framework = $this->getFrameworkInput();

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
        return file_exists($customPath = dirname(__FILE__, 3).$stub) ? $customPath : __DIR__ . $stub;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        // Merge the parent options with the additional options
        return array_merge(parent::getOptions(), [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The model associated with the view.'],
            ['route', 'r', InputOption::VALUE_OPTIONAL, 'The route name associated with the view.'],
            ['fields', 'f', InputOption::VALUE_OPTIONAL, 'Additional fields for the view in format: "name:name,type:text,default:null,col:12; name:email,type:email,col:6; name:city,type:select,col:6,options:blue|yellow|green|red|white"'],
        ]);
    }


    protected function replaceClass(string $stub, string $name): string
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
}