<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Support\Str;
use Laltu\LaravelMaker\Support\FieldParser;
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
//        $fieldParser = new FieldParser();
//        $fieldsString = "name:name;type:string;nullable:true, name:email;type:string, name:phone;type:string;nullable:true, name:users;type:hasOne;params:users|user_id|id, name:products;type:hasMany;params:products|user_id|id"; // Your example string
//
////        $fieldsArray = $fieldParser->parseFieldsToArray($fieldsString);
//        $fieldsArray = $fieldParser->processFields($fieldsString);
//        dd($fieldsArray);

        // Retrieving command arguments and options using getArgument and getOption methods
        $name = Str::snake(trim($this->argument('name')));
        $table = $this->option('table') ?: false;
        $create = $this->option('create') ?: false;
        $fields = $this->option('fields');

        $stub = $this->files->get($this->getStub());

        // Replace placeholders in the stub
        $stub = $this->replacePlaceholders($stub, $name, $table, $fields);

        // Determine the file path and name
        $fileName = date('Y_m_d_His') . '_' . $name . '.php';
        $filePath = $this->laravel->databasePath() . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . $fileName;

        // Write the file
        $this->files->put($filePath, $stub);

        $this->info("Migration {$fileName} created successfully.");
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

    protected function replacePlaceholders($stub, $name, $table, $fields): string
    {
        // Replace the table placeholder
        $stub = str_replace('{{ table }}', $table ?: 'your_table_name', $stub);

        // Replace the fields placeholder
        return $this->replaceFields($stub, $fields);
    }

    protected function replaceFields($stub, $fields): array|string
    {
        if (!$fields) {
            return str_replace(['{{ fields }}', '{{ fields_area }}'], '', $stub);
        }

        $fieldParser = new FieldParser();

        $formattedFields = $fieldParser->processFields($fields);

        return str_replace(['{{ fields }}', '{{ fields_area }}'], [$formattedFields, ''], $stub);
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
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model (colon-separated; ex: --fields="name:name;type:string;nullable:true; name:email;type:string; name:phone;type:string;nullable:true,name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id")'],
        ];
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

            } else {
                break; // Exit the loop if the user chooses "no".
            }
        }
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

    protected function confirmFieldAddition(): int|string
    {
        return select('Would you like to add more fields?', [
            'yes' => 'Yes',
            'no' => 'No',
        ], 'yes');
    }
}
