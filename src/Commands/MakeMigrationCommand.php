<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeMigrationCommand extends GeneratorCommand implements PromptsForMissingInput
{
    protected $name = 'make:schema';

    public function handle(): void
    {
        // Retrieving command arguments and options using getArgument and getOption methods
        $name = Str::snake(trim($this->argument('name')));
        $table = $this->option('table') ?: false;
        $create = $this->option('create') ?: false;
        $fields = $this->option('fields');
        $relations = $this->option('relations');

        $stub = $this->files->get($this->getStub());

        // Replace placeholders in the stub
        $stub = $this->replacePlaceholders($stub, $name, $table, $fields, $relations);

        // Determine the file path and name
        $fileName = date('Y_m_d_His') . '_' . $name . '.php';
        $filePath = $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $fileName;

        // Write the file
        $this->files->put($filePath, $stub);

        $this->info("Migration {$fileName} created successfully.");
    }

    protected function getArguments(): array
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the migration'],
        ];
    }

    protected function getOptions(): array
    {
        return [
            ['create', null, InputOption::VALUE_OPTIONAL, 'The table to be created', null],
            ['table', null, InputOption::VALUE_OPTIONAL, 'The table to migrate', null],
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model', null],
            ['relations', null, InputOption::VALUE_OPTIONAL, 'The relations fields for the model', null],
        ];
    }

    protected function replacePlaceholders($stub, $name, $table, $fields, $relations): string
    {
        // Replace the table placeholder
        $stub = str_replace('{{ table }}', $table ?: 'your_table_name', $stub);

        // Replace the fields placeholder
        $stub = $this->replaceFields($stub, $fields);

        // Replace the relations placeholder
        return $this->replaceRelations($stub, $relations);
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
        return file_exists($customPath = dirname(__FILE__, 3) . $stub) ? $customPath : __DIR__ . $stub;
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
    }

    protected function replaceTable($stub, $table): array|string
    {
        return str_replace('{{ table }}', $table, $stub);
    }


    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output): void
    {
        if ($this->isReservedName($this->getNameInput()) || $this->didReceiveOptions($input)) {
            return;
        }

        $option = select('Would you like any of the following?', [
            'fields' => 'Field',
            'relations' => 'Relationship',
        ]);

        $fields = [];
        $relations = [];


        if ($option == 'fields' && !$input->getOption('fields')) {
            $fields[] = $this->askForFields();
            // Set the joined fields as a single option value
            $input->setOption('fields', join(';', $fields));
        }

        if ($option == 'relations' && !$input->getOption('relations')) {
            $relations[] = $this->askForRelations();
            // Set the joined relations as a single option value
            $input->setOption('relations', join(';', $relations));
        }

        // Loop until the user chooses not to add more fields.
        while (true) {
            // Ask for more fields.
            if ($this->confirmFieldAddition() === 'yes') {

                $option = select('Would you like any of the following?', [
                    'fields' => 'Field',
                    'relations' => 'Relationship',
                    'no' => 'No',
                ]);

                if ($option == 'fields') {
                    $fields[] = $this->askForFields($input, $output);
                    // Set the joined fields as a single option value
                    $input->setOption('fields', join(';', $fields));
                }

                if ($option == 'relations') {
                    $relations[] = $this->askForRelations($input, $output);
                    // Set the joined relations as a single option value
                    $input->setOption('relations', join(';', $relations));
                }
            } else {
                break; // Exit the loop if the user chooses "no".
            }
        }
    }


    protected function confirmFieldAddition(): int|string
    {
        return select('Would you like to add more fields?', [
            'yes' => 'Yes',
            'no' => 'No',
        ], 'yes');
    }

    protected function askForFields(): string
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

    protected function askForRelations(): string
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
