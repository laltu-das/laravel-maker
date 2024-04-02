<?php

namespace Laltu\LaravelMaker\Livewire\Forms;

use Illuminate\Support\Str;
use Laltu\LaravelMaker\Models\Module;
use Livewire\Form;

class ModuleForm extends Form
{
    public ?Module $module;
    public string $module_name = '';
    public string $model_name = '';
    public string $route_name = '';
    public string $controller_name = '';
    public string $controller_type = '';
    public array $form_fields = [];
    public array $api_fields = [];

    public $rules = [
        'module_name' => 'required|string|max:255',
        'model_name' => 'required|string|max:255',
        'route_name' => 'required|string|max:255',
        'controller_name' => 'required|string|max:255',
        'controller_type' => 'required|string|max:255',
        'form_fields' => 'nullable',
        'api_fields' => 'nullable',
    ];

    public $controllerTypeOptions = [
        'increments' => 'Increments',
        'integer' => 'Integer',
    ];


    public $formFieldsFieldTypeOptions = [
        'button' => 'Button',
        'checkbox' => 'Checkbox',
        'color' => 'Color',
        'date' => 'Date',
        'datetime-local' => 'Datetime Local',
        'email' => 'Email',
        'file' => 'File',
        'hidden' => 'Hidden',
        'image' => 'Image',
        'month' => 'Month',
        'number' => 'Number',
        'password' => 'Password',
        'radio' => 'Radio',
        'range' => 'Range',
        'reset' => 'Reset',
        'search' => 'Search',
        'submit' => 'Submit',
        'tel' => 'Tel',
        'text' => 'Text',
        'time' => 'Time',
        'url' => 'URL',
        'week' => 'Week',
        'select' => 'Select',
    ];


    public $apiFieldsFieldTypeOptions = [
        'String' => 'String',
        'Integer' => 'Integer',
        'Float' => 'Float',
        'Boolean' => 'Boolean',
        'Array' => 'Array',
        'Object' => 'Object',
        'NULL' => 'NULL',
        'Resource' => 'Resource',
    ];

    public function setModule(Module $module): void
    {
        $this->module = $module;

        $this->fill($module);
    }

    public function addFormFieldRow(): void
    {
        $this->form_fields[] = $this->emptyFormField();
    }

    public function removeFormFieldRow(int $index): void
    {
        unset($this->form_fields[$index]);
        $this->form_fields = array_values($this->form_fields);
    }

    public function addApiFieldRow(): void
    {
        $this->api_fields[] = $this->emptyApiField();
    }

    public function removeApiFieldRow(int $index): void
    {
        unset($this->api_fields[$index]);
        $this->api_fields = array_values($this->api_fields);
    }

    private function emptyFormField(): array
    {
        return [
            'field_name' => '',
            'field_type' => '',
            'field_label' => '',
            'field_placeholder' => '',
            'validation' => '',
            'field_row' => '',
            'field_col' => '',
        ];
    }

    private function emptyApiField(): array
    {
        return [
            'field_name' => '',
            'field_type' => '',
            'validation' => '',
            'primary' => false,
            'is_foreign' => false,
            'searchable' => false,
            'fillable' => false,
            'in_form' => true,
            'in_index' => true,
            'db_type' => '',
            'html_type' => ''
        ];
    }

    public function store(): void
    {
        $validatedData = $this->validate();

        Module::create($validatedData);
    }

    public function update(): void
    {
        $validatedData = $this->validate();

        $this->module->update($validatedData);
    }

    protected function transformFieldNameToKebabCase($propertyName, $value)
    {
        $parts = explode('.', $propertyName);
        $type = $parts[0]; // 'form_fields' or 'api_fields'
        $index = $parts[1];
        $attribute = $parts[2];

        if ($type && $index !== null && $attribute === 'field_name') {
            $this->{$type}[$index][$attribute] = Str::kebab($value);
        }
    }

}
