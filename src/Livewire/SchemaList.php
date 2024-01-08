<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Laltu\LaravelMaker\Models\Schema;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class SchemaList extends Component
{
    public function render()
    {
        $schemas = Schema::get();

        return view('laravel-maker::livewire.schema-list', compact('schemas'))->layout(AppLayout::class);
    }

    public function makeModel(Schema $schema): int
    {
        $modelName = $schema->modelName;
        $fields = [];

        foreach ($schema->fields as $field) {
            $fieldName = $field['field_name'];
            $fieldType = $field['db_type'];

            $modifiers = [];

            if ($field['nullable']) {
                $modifiers[] = 'nullable';
            }

            if ($field['primary']) {
                $modifiers[] = 'primary';
            }
            // Add other modifiers based on your array structure

            $fields[] = "{$fieldName}:{$fieldType}" . (!empty($modifiers) ? ':' . implode(',', $modifiers) : '');
        }

        $relations = [];

        foreach ($schema->relationalFields as $relation) {
            $name = $relation['relationName'];
            $type = $relation['relationType']; // Example mapping

            $params = "{$relation['foreignModel']}|{$relation['localKey']}|{$relation['foreignKey']}";

            $relations[] = "name:{$name};type:{$type};params:{$params}";
        }

        $fieldsString = implode('; ', $fields);
        $relationsString = implode(',', $relations);

        $commandOptions = [];

        if (!empty($fields)) {
            $commandOptions[] = "--fields=\"{$fieldsString}\"";
        }

        if (!empty($relations)) {
            $commandOptions[] = "--relations=\"{$relationsString}\"";
        }

        $command = "make:model {$modelName} -mfs --force " . implode(' ', $commandOptions);

//        dump($command);

         return Artisan::call('generate:model', [
             'name' => $modelName,
             '--all' => true,
             '--force' => true
         ]);
    }
}