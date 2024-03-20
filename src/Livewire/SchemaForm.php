<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Str;
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
        'fields.*.fieldName' => 'required|string|max:255',
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

    public function updatedModelName($value): void
    {
        $this->modelName = Str::studly($value);
    }

    public function updated($propertyName, $value): void
    {
        if(str_starts_with($propertyName, 'fields.')) {
            // Assuming the update is to a field_name within fields
            $parts = explode('.', $propertyName);
            if (count($parts) === 3 && $parts[2] === 'fieldName') {
                $index = $parts[1];
                $this->fields[$index]['fieldName'] = Str::camel($value);
            }
        }
    }

    public function addFieldRow(): void
    {
        $this->fields[] = $this->emptyField();
    }

    public function addRelationshipFieldRow(): void
    {
        $this->fields[] = $this->emptyField('relationship');
    }

    protected function emptyField($field_type = ''): array
    {
        return [
            "fieldType" => $field_type,
            "fieldName" => "",
            "dataType" => "",
            "validation" => "",
            "searchable" => false,
            "fillable" => false,
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
