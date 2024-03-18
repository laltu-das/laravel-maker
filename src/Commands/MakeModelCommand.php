<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

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

        if ($this->option('fields')) {
            $this->createMigration();
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
     * Creates a migration for the specified table with the specified fields and relations
     *
     * @return void
     */
    protected function createMigration(): void
    {
        $fields = $this->option('fields');
        $relations = $this->option('relations');

        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        if ($this->option('pivot')) {
            $table = Str::singular($table);
        }

        $this->call('make:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--table' => $table,
            '--fields' => $fields,
            '--relations' => $relations,
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
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model (colon-separated; ex: --fields="name:string:nullable; email:string; phone:string:nullable")'],
            ['relations', null, InputOption::VALUE_OPTIONAL, 'The relations fields for the model (colon-separated; ex: --relations="name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id")'],
            ['with-inertia-resource', null, InputOption::VALUE_NONE, 'Generates a controller with resources(collection) for Inertia.js'],
            ['with-inertia-vue', null, InputOption::VALUE_NONE, 'Generates a controller with Vue.js support for Inertia.js'],
            ['with-inertia-react', null, InputOption::VALUE_NONE, 'Generates a controller with React.js support for Inertia.js'],
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
        if ($this->option('fields')) {
            return $this->resolveStubPath('/stubs/model.field.stub');
        } elseif ($this->option('relations')) {
            return $this->resolveStubPath('/stubs/model.relation.stub');
        } else {
            return parent::getStub();
        }
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3).$stub) ? $customPath : __DIR__ . $stub;
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

        if ($fields = $this->option('fields')) {
            $replace['{{ fillableFields }}'] = $this->buildFillableFields($fields);

            $replace['{{ casts }}'] = $this->buildCasts($fields);
        }

        if ($relations = $this->option('relations')) {
            $replace['{{ relations }}'] = $this->buildRelationMethods($relations);
        }

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    /**
     * Builds a string of fillable fields from a given string of fields separated by ";"
     *
     * @param string $fields The string of fields separated by ";"
     * @return string The built string of fillable fields
     */
    protected function buildFillableFields(string $fields): string
    {
        return collect(explode(';', $fields))
            ->map(function ($field) {
                return "'" . trim(explode(':', $field)[0]) . "'";
            })
            ->implode(', ');
    }

    /**
     * Builds a string of cast fields from a given string of fields separated by ";"
     *
     * @param string $fields The string of fields separated by ";"
     * @return string The built string of cast fields
     */
    protected function buildCasts(string $fields): string
    {
        return collect(explode(';', $fields))
            ->mapWithKeys(function ($field) {
                [$fieldName, $fieldType] = array_pad(explode(':', trim($field)), 2, 'string');
                $castType = $this->getLaravelCastType($fieldType);
                return [$fieldName => "'$castType'"];
            })
            ->map(function ($cast, $field) {
                return "'$field' => $cast";
            })
            ->implode(', ');
    }

    /**
     * Retrieves the corresponding Laravel cast type for a given field type
     *
     * @param string $fieldType The field type
     * @return string The corresponding Laravel cast type
     */
    protected function getLaravelCastType(string $fieldType): string
    {
        switch ($fieldType) {
            case 'integer':
            case 'bigint':
                return 'integer';
            case 'decimal':
            case 'float':
            case 'double':
                return 'float';
            // Add other cases as needed
            default:
                return 'string';
        }
    }

    /**
     * Builds relation methods based on the given string of relations separated by ";"
     *
     * @param string $relations The string of relations separated by ";"
     * @return string The built relation methods
     */
    protected function buildRelationMethods(string $relations): string
    {
        if (empty($relations)) {
            return '';
        }

        return collect(explode(';', $relations))
            ->map(function ($relation) {
                return collect(explode(',', trim($relation)))
                    ->mapWithKeys(function ($part) {
                        [$key, $value] = explode(':', $part);
                        return [$key => $value];
                    });
            })
            ->filter(function ($details) {
                return isset($details['name'], $details['type'], $details['params']);
            })
            ->map(function ($details) {
                [$relatedModel, $foreignKey, $ownerKey] = array_pad(explode('|', $details['params']), 3, null);

                $methodBody = "\$this->{$details['type']}($relatedModel::class";
                if (!is_null($foreignKey)) {
                    $methodBody .= ", '$foreignKey'";
                    if (!is_null($ownerKey)) {
                        $methodBody .= ", '$ownerKey'";
                    }
                }
                $methodBody .= ');';

                $methodStub = $this->getMethodStub();
                return str_replace(['{{ method_name }}', '{{ method_body }}'], [$details['name'], $methodBody], $methodStub);
            })
            ->implode(PHP_EOL);
    }

    /**
     * Retrieves the method stub from the specified path
     *
     * @return string The content of the method stub file
     */
    protected function getMethodStub(): string
    {
        return $this->resolveStubPath('/stubs/model.method.stub');
    }

}
