<?php

namespace Laltu\LaravelMaker\Livewire\Module;

use Laltu\LaravelMaker\Livewire\Forms\ModuleForm;
use Laltu\LaravelMaker\Models\Module;
use Livewire\Component;

class ModuleUpdate extends Component
{
    public ModuleForm $form;

    public function mount(Module $module): void
    {
        $this->form->setModule($module);
    }

    public function render()
    {
        return view('laravel-maker::livewire.module.module-form')->layout('laravel-maker::components.layouts.app');
    }
}
