<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Laltu\LaravelMaker\Services\ModelBuilder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\multiselect;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;

class MakeModelCommand extends ModelMakeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:model';

    /**
     * Handles the command execution by creating the necessary resources based on the available options.
     *
     * @return void
     */
    public function handle(): void
    {
        parent::handle();

        if ($this->option('all')) {
            $this->input->setOption('service', true);
            $this->input->setOption('action', true);
        }

        if ($this->option('service')) {
            $this->createService();
        }

        if ($this->option('action')) {
            $this->createAction();
        }
    }

    /**
     * Creates a new service.
     *
     * This method generates a service class using the "make:service" command.
     * The service class name is derived from the input name by applying "StudlyCase" formatting.
     * The generated service class will have pre-defined methods "get", "store", "show", "update", and "destroy".
     *
     * @return void
     */
    protected function createService(): void
    {
        $name = Str::studly($this->getNameInput());

        $this->call('make:service', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Creates a new action class using the "make:action" Artisan command
     */
    protected function createAction(): void
    {
        $name = Str::studly($this->getNameInput());

        $this->call('make:action', [
            'name' => "{$name}Action",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Retrieves an array containing the options for the current command
     *
     * @return array The options for the current command
     */
    public function getOptions(): array
    {
        // Merge the parent options with the additional options
        return array_merge(parent::getOptions(), [
            ['service', 'S', InputOption::VALUE_NONE, 'Generate a service for the model'],
            ['action', 'A', InputOption::VALUE_NONE, 'Generate a service for the model'],
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model (colon-separated; ex: --fields="name:name;type:string;nullable:true; name:email;type:string; name:phone;type:string;nullable:true")'],
            ['relations', null, InputOption::VALUE_OPTIONAL, 'The relations fields for the model (colon-separated; ex: --relations="name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id")'],
            ['with-inertia-resource', null, InputOption::VALUE_NONE, 'Generates a controller with resources(collection) for Inertia.js'],
            ['with-inertia-vue', null, InputOption::VALUE_NONE, 'Generates a controller with Vue.js support for Inertia.js'],
            ['with-inertia-react', null, InputOption::VALUE_NONE, 'Generates a controller with React.js support for Inertia.js'],
        ]);
    }

    /**
     * Creates a migration for the specified table with the specified fields and relations
     *
     * @return void
     */
    protected function createMigration(): void
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $fieldsString = Str::of($this->option('fields'))
            ->when($this->option('relations'), function ($string, $relations) {
                return $string->append(',', $relations);
            });

        $this->call('make:schema', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--table' => $table,
            '--fields' => $fieldsString,
        ]);
    }

    /**
     * Creates a controller using the given options and arguments
     *
     * @return void
     */
    protected function createController(): void
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('make:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--with-inertia-resource' => $this->option('with-inertia-resource'),
            '--with-inertia-vue' => $this->option('with-inertia-vue'),
            '--with-inertia-react' => $this->option('with-inertia-react'),
            '--test' => $this->option('test'),
            '--pest' => $this->option('pest'),
            '--force' => $this->option('force'),
        ]));
    }

    /**
     * Gets the appropriate stub path based on the options provided
     *
     * @return string The path to the desired stub file
     */
    protected function getStub(): string
    {

        if ($this->option('relations')) {
            return $this->resolveStubPath('/stubs/model.relation.stub');
        }

        if ($this->option('fields')) {
            return $this->resolveStubPath('/stubs/model.field.stub');
        }

        parent::getStub();
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3) . $stub) ? $customPath : __DIR__ . $stub;
    }

    /**
     * Builds the class file using the provided name and options
     *
     * @param string $name The name of the class being built
     * @return array|string The built class file
     * @throws FileNotFoundException
     */
    protected function buildClass($name): array|string
    {
        $replace = [];
        $modelBuilder = new ModelBuilder($this->option('fields'), $this->option('relations'));

        if ($this->option('fields')) {
            $replace['{{ fillableFields }}'] = $modelBuilder->buildFillableFields();
        }

        if ($this->option('relations')) {
            $replace['{{ relations }}'] = $modelBuilder->buildRelationMethods();
        }

        $replace['{{ casts }}'] = $modelBuilder->buildCasts();

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
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

        // Additional prompts for other options like seeds, factories, etc., can continue here
        collect(multiselect(
            label: 'Would you like any of the following?',
            options: [
                'all' => 'All',
                'seed' => 'Database Seeder',
                'factory' => 'Factory',
                'requests' => 'Form Requests',
                'migration' => 'Migration',
                'policy' => 'Policy',
                'resource' => 'Resource Controller',
                'service' => 'Resource Service',
                'action' => 'Resource Action',
            ],
        ))->each(fn($option) => $input->setOption($option, true));


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
                    $input->setOption('fields', join(',', $fields));
                }

                if ($option == 'relations') {
                    $relations[] = $this->askForRelations($input, $output);
                    // Set the joined relations as a single option value
                    $input->setOption('relations', join(',', $relations));
                }
            } else {
                break; // Exit the loop if the user chooses "no".
            }
        }

        dd($fields, $relations);
    }

    protected function askForFields(): string
    {
        $fieldTypes = ['string', 'integer', 'bigint', 'boolean', 'date', 'datetime', 'text', 'float', 'decimal', 'enum'];

        $name = text('Enter field name (or leave empty to finish):');

        $type = select('Select field type:', $fieldTypes);

        // Optionally, ask for additional attributes like nullable or default values
        $attributes = [];

        // Example: Asking for nullable attribute
        $isNull = confirm('Is this field nullable?');
        if ($isNull) {
            $attributes[] = ['nullable' => true];
        }

        // Example: Asking for nullable attribute
        $isUnique = confirm('Is this field isUnique?');
        if ($isUnique) {
            $attributes[] = ['unique' => true];
        }

        // Concatenate attributes if there are any
        $attributesString = collect($attributes)->implode(';', $attributes);

        // Append the new field to the fields array with its type and any attributes
        return "name:{$name};type:{$type};{$attributesString}";

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
        return "name:{$name};type:{$type}:{$relatedModel}";
    }

    protected function confirmFieldAddition(): int|string
    {
        return select('Would you like to add more fields?', [
            'yes' => 'Yes',
            'no' => 'No',
        ], 'yes');
    }

}
