<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Facades\LaravelMaker;

use Livewire\Component;

class ModuleFormBuilder extends Component
{
    public $module;

    public array $formFields = [];

    public function mount($moduleName): void
    {
        if ($moduleName) {
            $module = $this->module = LaravelMaker::loadModule($moduleName);

            $this->fill(['formFields' => $module->formFields??[]]);
        }
    }

    public function addFormFieldRow(): void
    {
        $this->formFields[] = [
            "fieldName" => "",
            "fieldType" => "",
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
        unset($this->formFields[$index]);

        $this->formFields = array_values($this->formFields); // Re-index the array
    }

    public function update($module): void
    {
        $module->update([
            'formFields' => $this->formFields,
        ]);

        $this->js("alert('Update saved!')");

    }

    public function show($index): void
    {
        $this->dispatch('open-side-panel', 'New Title')->component(ModuleValidation::class);
    }

    public function render()
    {
        return view('laravel-maker::livewire.module-form-builder')->layout('laravel-maker::components.layouts.app');
    }
}