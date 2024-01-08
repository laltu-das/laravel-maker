<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class GenerateFactoryCommand extends FactoryMakeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'generate:factory';

    /**
     * Execute the console command.
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        parent::handle();
    }

    protected function replaceFields($fields): array|string
    {
        $fieldArray = array_map('trim', explode(';', $fields));

        return collect($fieldArray)->map(function ($field) {
            return '            ' . $this->processField($field); // Add indentation
        })->implode(",\n");
    }

    protected function processField($field): string
    {
        [$fieldName, $fieldType] = explode(':', $field);

        switch ($fieldType) {
            case 'string':
                $fakerMethod = $fieldName == 'email' ? '$this->faker->unique()->safeEmail' : '$this->faker->name';
                break;
            // Add other cases for different field types
            default:
                $fakerMethod = '$this->faker->text';
        }

        return "'$fieldName' => $fakerMethod";
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        if ($this->option('fields')) {
            return $this->resolveStubPath('/stubs/model-factory.stub');
        }

        return $this->resolveStubPath('/stubs/factory.stub');
    }

    public function getOptions(): array
    {
        $options = parent::getOptions();

        $options[] = ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'];
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
            $replace['{{ fields }}'] = $this->replaceFields($fields);
        }

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

}
