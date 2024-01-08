<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Foundation\Console\ModelMakeCommand;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

class GenerateModelCommand extends ModelMakeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:model';

    /**
     * Execute the console command.
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
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createService(): void
    {
        $name = Str::studly($this->getNameInput());

        $this->call('generate:service', [
            'name' => "{$name}Service",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }

    /**
     * Create a model factory for the model.
     *
     * @return void
     */
    protected function createAction(): void
    {
        $name = Str::studly($this->getNameInput());

        $this->call('generate:action', [
            'name' => "{$name}Action",
            '--methods' => "get,store,show,update,destroy",
        ]);
    }


    /**
     * Create a migration file for the model.
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

        $this->call('generate:migration', [
            'name' => "create_{$table}_table",
            '--create' => $table,
            '--table' => $table,
            '--fields' => $fields,
            '--relations' => $relations,
        ]);
    }

    /**
     * Create a controller for the model.
     *
     * @return void
     */
    protected function createController(): void
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('generate:controller', array_filter([
            'name' => "{$controller}Controller",
            '--model' => $this->option('resource') || $this->option('api') ? $modelName : null,
            '--api' => $this->option('api'),
            '--requests' => $this->option('requests') || $this->option('all'),
            '--test' => $this->option('test'),
            '--pest' => $this->option('pest'),
            '--inertia' => true,
            '--force' => $this->option('force'),
        ]));
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();

        $options[] = ['service', 'S', InputOption::VALUE_NONE, 'Generate a service for the model'];
        $options[] = ['action', 'A', InputOption::VALUE_NONE, 'Generate a service for the model'];
        $options[] = ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model (colon-separated; ex: --fields="name:string:nullable; email:string; phone:string:nullable")'];
        $options[] = ['relations', null, InputOption::VALUE_OPTIONAL, 'The relations fields for the model (colon-separated; ex: --relations="name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id")'];

        return $options;
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/'))) ? $customPath : __DIR__ . $stub;
    }

    protected function buildClass($name): array|string
    {
        $replace = [];

        if ($fields = $this->option('fields')) {
            $replace['{{ fillableFields }}'] = $this->buildFillableFields($fields);
        }

        if ($relations = $this->option('relations')) {
            $replace['{{ relations }}'] = $this->buildRelationMethods($relations);  // Add casting logic
        }

        $replace['{{ casts }}'] = $this->buildCasts($fields);  // Add casting logic

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

    protected function buildCasts($fields): string
    {
        $fieldsArray = array_map('trim', explode(';', $fields));

        $castsArray = collect($fieldsArray)->mapWithKeys(function ($field) {
            $parts = explode(':', $field);
            $fieldName = $parts[0];
            $fieldType = $parts[1] ?? 'string';

            // Determine the Laravel equivalent cast type
            $castType = $this->getLaravelCastType($fieldType);

            return [$fieldName => "'$castType'"];
        });

        // Format the array into a string
        return $castsArray->map(function ($cast, $field) {
                return "'$field' => $cast";
            })->implode(', ');
    }

    protected function buildRelationMethods($relations): string
    {
        if (empty($relations)) {
            return '';
        }

        $relationMethods = '';
        $relationArray = explode(';', $relations);

        foreach ($relationArray as $relation) {
            $parts = explode(',', trim($relation));
            $details = [];
            foreach ($parts as $part) {
                [$key, $value] = explode(':', $part);
                $details[$key] = $value;
            }

            if (!isset($details['name'], $details['type'], $details['params'])) {
                // Invalid format or missing data
                continue;
            }

            $params = explode('|', $details['params']);
            $relatedModel = $params[0];
            $foreignKey = $params[1] ?? null;
            $ownerKey = $params[2] ?? null;

            $methodBody = "\$this->{$details['type']}($relatedModel::class";

            if (!is_null($foreignKey)) {
                $methodBody .= ", '$foreignKey'";
                if (!is_null($ownerKey)) {
                    $methodBody .= ", '$ownerKey'";
                }
            }

            $methodBody .= ');';

            $relationMethods .= "
    public function {$details['name']}()
    {
        return $methodBody
    }
";
        }
        dump($relationArray);

        return $relationMethods;
    }

    protected function getLaravelCastType($fieldType): string
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

    protected function buildFillableFields($fields): string
    {
        $fieldsArray = array_map('trim', explode(';', $fields));

        return collect($fieldsArray)->map(function ($field) {
            $parts = explode(':', $field);
            $fieldName = $parts[0];
            return "'$fieldName'";
        })->implode(', ');
    }
}
