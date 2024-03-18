<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;
use Symfony\Component\Console\Input\InputOption;

class MakeFactoryCommand extends FactoryMakeCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:factory';

    /**
     * Handle the command.
     *
     * @return void
     * @throws FileNotFoundException
     */
    public function handle(): void
    {
        parent::handle();
    }

    /**
     * Replace fields in a given string or array.
     *
     * @param array|string $fields The string or array containing the fields to replace.
     *
     * @return array|string The string or array with the replaced fields. If the input was a string, the result is a string;
     *                      if the input was an array, the result is an array.
     */
    protected function replaceFields(array|string $fields): array|string
    {
        $fieldArray = array_map('trim', explode(';', $fields));

        return collect($fieldArray)->map(function ($field) {
            return '            ' . $this->processField($field); // Add indentation
        })->implode(",\n");
    }

    /**
     * Process a field and generate a value using Faker.
     *
     * @param string $field - The field to process in the format 'fieldName:fieldType'.
     * @return string - The processed field value in the format "'fieldName' => $fakerMethod".
     */
    protected function processField(string $field): string
    {
        [$fieldName, $fieldType] = explode(':', $field);

        $fakerMethod = match ($fieldType) {
            'string' => $fieldName == 'email' ? '$this->faker->unique()->safeEmail' : '$this->faker->name',
            default => '$this->faker->text',
        };

        return "'$fieldName' => $fakerMethod";
    }

    /**
     * Get the appropriate stub file path based on the given fields option.
     *
     * @return string The stub file path.
     */
    protected function getStub(): string
    {
        if ($this->option('fields')) {
            return $this->resolveStubPath('/stubs/model-factory.stub');
        }

        return $this->resolveStubPath('/stubs/factory.stub');
    }

    /**
     * Get the options for the command.
     *
     * @return array
     */
    public function getOptions(): array
    {
        $options = parent::getOptions();

        $options[] = ['force', null, InputOption::VALUE_NONE, 'Create the class even if the controller already exists'];
        $options[] = ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields for the model (colon-separated; ex: --fields="name:string:nullable; email:string; phone:string:nullable")'];
        $options[] = ['relations', null, InputOption::VALUE_OPTIONAL, 'The relations fields for the model (colon-separated; ex: --relations="name:users;type:hasOne;params:users|user_id|id,name:products;type:hasMany;params:products|user_id|id")'];

        return $options;
    }

    /**
     * Resolve the path for a stub file.
     *
     * @param string $stub The path to the stub file.
     * @return string The resolved path for the stub file.
     */
    protected function resolveStubPath($stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3).$stub) ? $customPath : __DIR__ . $stub;
    }

    /**
     * Builds the class.
     *
     * This method replaces the placeholder '{{ fields }}' in the parent class with the
     * provided fields. It returns the built class.
     *
     * @param string $name The name of the class.
     * @return array|string The built class.
     */
    protected function buildClass($name): array|string
    {
        $replace = [];

        if ($fields = $this->option('fields')) {
            $replace['{{ fields }}'] = $this->replaceFields($fields);
        }

        return str_replace(array_keys($replace), array_values($replace), parent::buildClass($name));
    }

}
