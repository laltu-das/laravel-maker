<?php

namespace Laltu\LaravelMaker\Livewire\Module;

use Livewire\Attributes\On;
use Livewire\Component;

class ModuleValidation extends Component
{
    public bool $open = false;
    public string $title = 'Default Panel';


    #[On('open-side-panel')]
    public function openPanel(string $title): void
    {
        $this->open = true;
        $this->title = $title;
    }

    public function render()
    {
        return view('laravel-maker::livewire.module-validation')->layout('laravel-maker::components.layouts.app');
    }
}