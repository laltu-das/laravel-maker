<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\Artisan;
use Laltu\LaravelMaker\Models\Schema;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class SchemaList extends Component
{

    public function render()
    {
        $schemas = Schema::latest()->paginate();

        return view('laravel-maker::livewire.schema-list', compact('schemas'))->layout(AppLayout::class);
    }


    public function makeModel($schemaName): void
    {
        $commandOptions = [
            'name' => $schema->modelName,
            '--force' => true
        ];

        Artisan::call('generate:model', $commandOptions);

        $this->js("alert('Schema saved!')");
    }

    public function makeFactory($schemaName): void
    {
        [$fieldsString, $relationsString] = $this->prepareFieldsAndRelations($schema);

        $commandOptions = [
            'name' => "{$schema->modelName}Resource",
            '--model' => $schema->modelName,
            '--force' => true
        ];

        if (!empty($fieldsString)) {
            $commandOptions["--fields"] = $fieldsString;
        }

        if (!empty($relationsString)) {
            $commandOptions["--relations"] = $relationsString;
        }

        Artisan::call('generate:factory', $commandOptions);

        $this->js("alert('Schema saved!')");
    }

    public function makeSeeder($schemaName): void
    {
        [$fieldsString, $relationsString] = $this->prepareFieldsAndRelations($schema);

        $commandOptions = [
            'name' => "{$schema->modelName}Resource",
            '--model' => $schema->modelName,
            '--force' => true
        ];

        if (!empty($fieldsString)) {
            $commandOptions["--fields"] = $fieldsString;
        }

        if (!empty($relationsString)) {
            $commandOptions["--relations"] = $relationsString;
        }

        Artisan::call('generate:seeder', $commandOptions);

        $this->js("alert('Schema saved!')");
    }

    public function makeService($schemaName): void
    {
        [$fieldsString, $relationsString] = $this->prepareFieldsAndRelations($schema);

        $commandOptions = [
            'name' => "{$schema->modelName}Resource",
            '--model' => $schema->modelName,
            '--force' => true
        ];

        if (!empty($fieldsString)) {
            $commandOptions["--fields"] = $fieldsString;
        }

        if (!empty($relationsString)) {
            $commandOptions["--relations"] = $relationsString;
        }

        Artisan::call('generate:service', $commandOptions);

        $this->js("alert('Schema saved!')");
    }

    public function makeResource($schemaName): void
    {
        [$fieldsString, $relationsString] = $this->prepareFieldsAndRelations($schema);

        $commandOptions = [
            'name' => "{$schema->modelName}Resource",
            '--model' => $schema->modelName,
            '--force' => true
        ];

        if (!empty($fieldsString)) {
            $commandOptions["--fields"] = $fieldsString;
        }

        if (!empty($relationsString)) {
            $commandOptions["--relations"] = $relationsString;
        }

        Artisan::call('generate:resource', $commandOptions);

        $this->js("alert('Schema saved!')");
    }

    public function makeAll($schemaName): void
    {
        $schema = $this->getSchema($schemaName);

        if ($schema) {
            // Your existing logic here...
            $modelName = $schema['modelName'];
            // Call Artisan commands or any other operations based on the schema
            $modelName = $schema->modelName;
            [$fieldsString, $relationsString] = $this->prepareFieldsAndRelations($schema);

            $commandOptions = [
                'name' => $modelName,
                '--all' => true,
                '--force' => true
            ];

            if (!empty($fieldsString)) {
                $commandOptions["--fields"] = $fieldsString;
            }

            if (!empty($relationsString)) {
                $commandOptions["--relations"] = $relationsString;
            }

            Artisan::call('generate:model', $commandOptions);

            $this->js("alert('Schema saved!')");
        } else {
            $this->js("alert('Schema not found!')");
        }
    }

    public function makePolicy($schemaName): void
    {
        $modelName = $schema->modelName;
        [$fieldsString, $relationsString] = $this->prepareFieldsAndRelations($schema);

        $commandOptions = [
            'name' => "{$modelName}Policy",
            '--model' => $modelName,
            '--force' => true
        ];

        if (!empty($fieldsString)) {
            $commandOptions["--fields"] = $fieldsString;
        }

        if (!empty($relationsString)) {
            $commandOptions["--relations"] = $relationsString;
        }

        Artisan::call('generate:policy', $commandOptions);

        $this->js("alert('Schema saved!')");
    }

    private function prepareFieldsAndRelations($schemaName): array
    {
        return [
            $this->prepareFields($schema->fields),
            $this->prepareRelations($schema->relationalFields)
        ];
    }

    private function prepareFields($fields): string
    {
        return collect($fields)
            ->map(fn($field) => $this->prepareField($field))
            ->implode('; ');
    }

    private function prepareField(array $field): string
    {
        $fieldName = $field['field_name'];
        $fieldType = $field['db_type'];
        $modifiers = collect($field)
            ->only(['nul34lable', 'primary'])
            ->filter()
            ->keys()
            ->implode(',');

        return "{$fieldName}:{$fieldType}" . ($modifiers ? ":{$modifiers}" : '');
    }

    private function prepareRelations($relationalFields): string
    {
        return collect($relationalFields)
            ->map(function ($relation) {
                $name = $relation['relationName'];
                $type = $relation['relationType'];
                $params = "{$relation['foreignModel']}|{$relation['localKey']}|{$relation['foreignKey']}";
                return "name:{$name};type:{$type};params:{$params}";
            })
            ->implode(',');
    }
}