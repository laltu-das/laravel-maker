<?php

namespace Laltu\LaravelMaker\Livewire\Forms;

use Laltu\LaravelMaker\Models\Module;
use Livewire\Form;

class ModuleForm extends Form
{

    public ?Module $module;

    public $model_name = '';
    public $controller_name = '';
    public $controller_type = '';

    public array $controllerTypeOptions = [
        "increments" => "Increments",
        "integer" => "Integer",
    ];

    public function rules(): array
    {
        return [
            'model_name' => 'required|string|max:255',
            'controller_type' => 'required|string|max:255',
            'controller_name' => 'required|string|max:255',
        ];
    }

    public function addFieldRow(): void
    {
        $this->fields[] = $this->emptyField() + ['id' => uniqid()];
    }

    protected function emptyField($field_type = ''): array
    {
        return [
            'id' => uniqid(),
            "fieldType" => $field_type,
            "fieldName" => "",
            "dataType" => "",
            "validation" => "",
            "searchable" => false,
            "fillable" => false,
            "relationName" => "",
            "foreignModelOptions" => [],
            "foreignModel" => "",
            "foreignKey" => "",
            "localKey" => "",
            "relationType" => "",
            "nullable" => false,
        ];
    }

    public function addRelationshipFieldRow(): void
    {
        $this->fields[] = $this->emptyField('relationship') + ['id' => uniqid(), 'foreignModelOptions' => module::pluck('modelName', 'id')];
    }

    public function removeFormFieldRow(int $index): void
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function setModule(Module $module): void
    {
        $this->module = $module;

        $this->fill($module);
    }

    public function store(): void
    {
        $this->validate();

        Module::create($this->only(['model_name', 'controller_type', 'controller_type']));
    }

    public function update(): void
    {
        $this->validate();

        $this->module->update(
            $this->all()
        );

        $this->reset();
    }
}
