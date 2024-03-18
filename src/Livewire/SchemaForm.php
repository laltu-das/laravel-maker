<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Models\Schema;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class SchemaForm extends Component
{
    public $schema;
    public array $fields = [];
    public string $modelName = '';
    public string $mode;

    protected $rules = [
        'modelName' => 'required|string|max:255',
        'fields.*.field_name' => 'required|string|max:255',
        // Extend validation rules for the fields array as necessary
    ];

    public function mount(?int $schemaId = null): void
    {
        $this->schema = Schema::findOrNew($schemaId);
        $this->mode = $schemaId ? 'edit' : 'add';
        if ($this->mode === 'edit') {
            $this->fill([
                'modelName' => $this->schema->modelName,
                'fields' => $this->schema->fields ?? [$this->emptyField()]
            ]);
        } else {
            $this->fields = [$this->emptyField()];
        }
    }

    public function addFormFieldRow(): void
    {
        $this->fields[] = $this->emptyField();
    }

    protected function emptyField(): array
    {
        return [
            "field_name" => "",
            "validation" => "",
            "primary" => false,
            "is_foreign" => false,
            "searchable" => false,
            "fillable" => false,
            "in_form" => true,
            "in_index" => true,
            "db_type" => "",
            "html_type" => "",
            "relationName" => "",
            "foreignModel" => "",
            "foreignKey" => "",
            "localKey" => "",
            "relationType" => "",
            "nullable" => false,
        ];
    }

    public function removeFormFieldRow(int $index): void
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function save()
    {
        $this->validate();

        $this->schema->fill([
            'modelName' => $this->modelName,
            'fields' => $this->fields,
        ])->save();

        session()->flash('message', 'Schema ' . ($this->mode === 'edit' ? 'updated' : 'created') . ' successfully.');

        return redirect()->route('schema');
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema-form')->layout(AppLayout::class);
    }
}
