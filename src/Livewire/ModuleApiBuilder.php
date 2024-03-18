<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Facades\LaravelMaker;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ModuleApiBuilder extends Component
{
    public $module;

    public array $apiFields = [[
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
    ]];

    public function mount($moduleName): void
    {
        if ($moduleName) {
            $module = $this->module = LaravelMaker::loadModule($moduleName);

            $this->fill(['apiFields' => $module->apiFields ?? []]);
        }
    }


    public function addFormFieldRow(): void
    {
        $this->apiFields[] = [
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
        unset($this->apiFields[$index]);

        $this->apiFields = array_values($this->apiFields);
    }

    public function update($module): void
    {
        $module->update([
            'apiFields' => $this->apiFields,
        ]);

        $this->js("alert('Update saved!')");
    }

    public function show(): void
    {
        $this->dispatch('side-panel', 'New Title', '')->component(SidePanel::class);
    }

    public function render()
    {
        return view('laravel-maker::livewire.module-api-builder')->layout(AppLayout::class);
    }
}