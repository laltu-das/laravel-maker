<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Models\Module;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ModuleList extends Component
{
    public function render()
    {
        $modules = Module::get();

        return view('laravel-maker::livewire.module-list', compact('modules'))->layout(AppLayout::class);
    }
}