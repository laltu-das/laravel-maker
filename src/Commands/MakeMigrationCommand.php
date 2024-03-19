<?php

namespace Laltu\LaravelMaker\Commands;

use Exception;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeMigrationCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:migration';

    /**
     * Execute the console command.
     * @throws Exception
     */
    public function handle(): void
    {
        $name = Str::snake(trim($this->input->getArgument('name')));

        $table = $this->input->getOption('table');

        $create = $this->input->getOption('create') ?: false;

        $fields = $this->option('fields');
        $relations = $this->option('relations');

        $this->writeMigrationWithFields($name, $table, $create, $fields, $relations);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the migration.'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['create', null, InputOption::VALUE_REQUIRED, 'The table to be created.'],
            ['table', null, InputOption::VALUE_REQUIRED, 'The table to migrate.'],
            ['path', null, InputOption::VALUE_REQUIRED, 'The location where the migration file should be created.'],
            ['realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths.'],
            ['fields', null, InputOption::VALUE_REQUIRED, 'The fields for the model (colon-separated; ex: --fields="name:string:nullable; email:string; phone:string:nullable").'],
            ['relations', null, InputOption::VALUE_REQUIRED, 'The relations fields for the model (colon-separated; ex: --relations="users:hasOne:users|user_id|id,products:hasMany:products|user_id|id").'],
        ];
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

    /**
     * Perform actions after the user was prompted for missing arguments.
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        $input->setOption('queue', confirm(
            label: 'Would you like to queue the mail?',
            default: $this->option('queue')
        ));

        $fields = [];
        $relations = [];

        $option = select('Would you like any of the following?', [
            'field' => 'Field',
            'relationship' => 'Relationship',
            'no' => 'No',
        ]);

        if ($option == 'field') {
            $fields[] = $this->askForFields($input, $output);

            // Set the joined fields as a single option value
            $input->setOption('fields', join(';', $fields));
        }

        if ($option == 'relationship') {
            $relations[] = $this->askForRelations($input, $output);
            // Set the joined relations as a single option value
            $input->setOption('relations', join(';', $relations));
        }
    }

    protected function askForFields(InputInterface $input, OutputInterface $output): string
    {
        $fieldTypes = ['string', 'integer', 'bigint', 'boolean', 'date', 'datetime', 'text', 'float', 'decimal', 'enum'];

        $name = text('Enter field name (or leave empty to finish):');

        $type = select('Select field type:', $fieldTypes);

        // Optionally, ask for additional attributes like nullable or default values
        $attributes = [];

        // Example: Asking for nullable attribute
        $isNullable = confirm('Is this field nullable?');
        if ($isNullable) {
            $attributes[] = 'nullable';
        }

        // Concatenate attributes if there are any
        $attributesString = $attributes ? ':' . implode(':', $attributes) : '';

        // Append the new field to the fields array with its type and any attributes
        return "{$name}:{$type}{$attributesString}";

    }

    protected function askForRelations(InputInterface $input, OutputInterface $output): string
    {
        // Asking for relations
        $relationTypes = ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 'morphTo', 'morphMany', 'morphToMany'];

        // Assume we keep asking for relations until the user decides to stop

        $name = text('Enter relation name (or leave empty to finish):');

        $type = select('Select relation type:', $relationTypes);
        $relatedModel = text('Enter related model (e.g., App\Models\User):');

        // Append the new relation to the relations array
        return "{$name}:{$type}:{$relatedModel}";
    }




}
