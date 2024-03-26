<?php

namespace Laltu\LaravelMaker\Livewire\Module;

use Laltu\LaravelMaker\Livewire\Forms\ModuleForm;
use Laltu\LaravelMaker\Models\Module;
use Livewire\Component;

class ModuleFormBuilder extends Component
{
    public ModuleForm $form;

    public function mount(Module $module)
    {
        $this->form->setModule($module);
    }

    public function addFormFieldRow(): void
    {
        $this->form->addFormFieldRow();
    }

    public function removeFormFieldRow(int $index): void
    {
        $this->form->removeFormFieldRow($index);
    }

    public function update(): void
    {
        $this->form->update();

        $this->js("alert('Update saved!')");

    }

    public function render()
    {
        return view('laravel-maker::livewire.module.module-form-builder')->layout('laravel-maker::components.layouts.app');
    }
}