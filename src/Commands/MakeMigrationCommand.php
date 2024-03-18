<?php

namespace Laltu\LaravelMaker\Commands;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Migrations\MigrateMakeCommand;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;

class MakeMigrationCommand extends MigrateMakeCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:migration';

//    /**
//     * The name and signature of the console command.
//     *
//     * @var string
//     */
//    protected $signature = 'make:migration {name : The name of the migration}
//        {--create= : The table to be created}
//        {--table= : The table to migrate}
//        {--path= : The location where the migration file should be created}
//        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
//        {--fullpath : Output the full path of the migration (Deprecated)}
//        {--fields= : The fields for the model (colon-separated; ex: --fields="name:string:nullable; email:string; phone:string:nullable")}
//        {--relations= : The relations fields for the model (colon-separated; ex: --relations="name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id}';
//

    protected Filesystem $files;

    public function __construct(MigrationCreator $creator, Composer $composer)
    {
        parent::__construct($creator, $composer);
    }


    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
//        parent::handle();
        $name = Str::snake(trim($this->input->getArgument('name')));

        $table = $this->input->getOption('table');

        $create = $this->input->getOption('create') ?: false;

        $fields = $this->option('fields');
        $relations = $this->option('relations');

        $this->writeMigrationWithFields($name, $table, $create, $fields, $relations);
    }

    /**
     * @throws FileNotFoundException
     */
    private function writeMigrationWithFields($name, $table, $create, $fields = null, $relations = null): void
    {
        $stub = $this->files->get($this->getStub());

        $stub = $this->replaceTable($stub, $table);
        $stub = $this->replaceFields($stub, $fields);
        $stub = $this->replaceRelations($stub, $relations);

        $fileName = date('Y_m_d_His') . '_' . $name . '.php';
        $filePath = $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $fileName;

        $this->files->put($filePath, $stub);

        $this->components->info(sprintf('Migration [%s] created successfully.', $fileName));

//        $file = $this->creator->create(
//            $name, $this->getMigrationPath(), $table, $create
//        );
//
//        $this->components->info(sprintf('Migration [%s] created successfully.', $file));
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('fields')) {
            return $this->resolveStubPath('/stubs/model-migration.create.stub');
        }

        return $this->resolveStubPath('/stubs/migration.create.stub');
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3).$stub) ? $customPath : __DIR__ . $stub;
    }

    protected function replaceFields($stub, $fields): array|string
    {
        if (!$fields) {
            return str_replace(['{{ fields }}', '{{ fields_area }}'], '', $stub);
        }

        $fieldArray = array_map('trim', explode(';', $fields));

        $formattedFields = collect($fieldArray)->map(function ($field) {
            return $this->processField($field);
        })->implode("\n            ");

        return str_replace(['{{ fields }}', '{{ fields_area }}'], [$formattedFields, ''], $stub);
    }

    protected function processField($fieldDefinition): string
    {
        $parts = explode(':', $fieldDefinition);
        $fieldName = $parts[0];
        $fieldType = $parts[1] ?? 'string';
        $fieldOptions = $parts[2] ?? null;

        $fieldLine = "\$table->$fieldType('$fieldName')";

        if (Str::startsWith($fieldOptions, 'nullable')) {
            $fieldLine .= "->{$fieldOptions}()";
        }

        if (Str::startsWith($fieldOptions, 'unique')) {
            $fieldLine .= "->{$fieldOptions}()";
        }

        return $fieldLine . ';';
    }

    protected function processRelationField($relations): string
    {
        // Split the relation string by commas and then find the part with "params:"
        $parts = explode(',', $relations);
        $paramsPart = collect($parts)->first(function ($part) {
            return Str::startsWith($part, 'params:');
        });

        // Extract the params values
        if ($paramsPart) {
            $params = explode(':', $paramsPart)[1];
            list($foreignTable, $foreignKey, $localKey) = explode('|', $params);

            // Construct the foreign key line
            return "\$table->foreignId('$foreignKey')->constrained('$foreignTable', '$localKey')->cascadeOnUpdate()->cascadeOnDelete();";
        }

        return '';
    }

    protected function replaceRelations($stub, $relations): array|string
    {
        // New logic for handling relations (foreign keys)
        if ($relations) {
            $relationFields = collect(explode(';', $relations))->map(function ($relation) {
                return $this->processRelationField($relation);
            })->implode("\n            ");

            return str_replace('{{ relations }}', $relationFields, $stub);
        } else {
            return str_replace('{{ relations }}', '', $stub);
        }

//        dump($fieldArray);
//        return str_replace(['{{ relations }}', '{{ relations_area }}'], $formattedRelations, $stub);
    }

    protected function replaceTable($stub, $table): array|string
    {
        return str_replace('{{ table }}', $table, $stub);
    }

}
