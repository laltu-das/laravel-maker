<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\Route;
use Laltu\LaravelMaker\Models\Module;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Attributes\On;
use Livewire\Component;

class ModuleFormBuilder extends Component
{
    public Module $module;

    public $formFields = [
        [
            "fieldName" => "",
            "fieldType" => "",
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

    public function mount(Module $module): void
    {
        $this->fill(['formFields' => $module->formFields??[]]);
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

    public function update(Module $module): void
    {
        $module->update([
            'formFields' => $this->formFields,
        ]);

        $this->js("alert('Update saved!')");

    }

    public function show(): void
    {
        $this->dispatch('open-side-panel', 'New Title')->component(ModuleValidation::class);
    }

    public function render()
    {
        return view('laravel-maker::livewire.module-form-builder')->layout(AppLayout::class);
    }
}