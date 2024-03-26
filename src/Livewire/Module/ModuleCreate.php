<?php

namespace Laltu\LaravelMaker\Livewire\Module;

use Laltu\LaravelMaker\Livewire\Forms\ModuleForm;
use Livewire\Component;

class ModuleCreate extends Component
{
    public ModuleForm $form;

    public function save()
    {
        $this->form->store();

        session()->flash('message', 'Module created successfully.');

        return redirect()->route('laravel-maker.module');
    }

    public function render()
    {
        return view('laravel-maker::livewire.module.module-form')->layout('laravel-maker::components.layouts.app');
    }
}
