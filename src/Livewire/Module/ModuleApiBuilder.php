<?php

namespace Laltu\LaravelMaker\Livewire\Module;

use Laltu\LaravelMaker\Livewire\Forms\ModuleForm;
use Laltu\LaravelMaker\Models\Module;
use Livewire\Component;

class ModuleApiBuilder extends Component
{
    public ModuleForm $form;

    public function mount(Module $module)
    {
        $this->form->setModule($module);
    }

    public function addApiFieldRow(): void
    {
        $this->form->addApiFieldRow();
    }

    public function removeApiFieldRow(int $index): void
    {
        $this->form->removeApiFieldRow($index);
    }

    public function update(): void
    {
        $this->form->update();

        $this->js("alert('Update saved!')");
    }

    public function render()
    {
        return view('laravel-maker::livewire.module.module-api-builder')->layout('laravel-maker::components.layouts.app');
    }
}