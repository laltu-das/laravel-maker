<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Laltu\LaravelMaker\Models\Schema;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class SchemaCreate extends Component
{
    public Schema $schema;

    public $modelName;

    public $mode = 'add';

    public $fields = [
        [
            "field_name" => "",
            "validation" => "",
            "primary" => true,
            "is_foreign" => true,
            "searchable" => false,
            "fillable" => false,
            "in_form" => true,
            "in_index" => true,
            "db_type" => "",
            "html_type" => ""
        ]
    ];

    public $relationalFields = [];

    public function mount(): void
    {
        $schema = Route::current()->parameter('schema');

        if ($schema) {
            $this->mode = 'edit';
            $this->fill($this->schema);
        }
    }

    public function addFormFieldRow(): void
    {
        $this->fields[] = [
            "field_name" => "",
            "validation" => "",
            "primary" => true,
            "is_foreign" => true,
            "searchable" => false,
            "fillable" => false,
            "in_form" => true,
            "in_index" => true,
            "db_type" => "",
            "html_type" => ""
        ];
    }

    public function removeFormFieldRow($index): void
    {
        unset($this->fields[$index]);

        $this->fields = array_values($this->fields); // Re-index the array
    }

    public function addFormRelationalFieldRow(): void
    {
        $this->relationalFields[] = [
            "foreign_model" => "",
            "foreign_key" => "",
            "local_key" => "",
            "relation_type" => "",
        ];
    }

    public function removeFormRelationalFieldRow($index): void
    {
        unset($this->relationalFields[$index]);

        $this->relationalFields = array_values($this->relationalFields);
    }

    public function create(): void
    {
        Schema::create([
            'modelName' => $this->modelName,
            'fields' => $this->fields,
            'relationalFields' => $this->relationalFields,
        ]);

        $this->redirect(route('schema'), true);
    }

    public function update(Schema $schema): void
    {
        $schema->update([
            'modelName' => $this->modelName,
            'fields' => $this->fields,
            'relationalFields' => $this->relationalFields,
        ]);

        $this->redirect(route('schema'), true);
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema-create')->layout(AppLayout::class);
    }
}