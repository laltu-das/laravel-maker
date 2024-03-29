<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Contracts\Support\Renderable;

use Livewire\Component;

class Dashboard extends Component
{
    /**
     * Render the component.
     */
    public function render(): Renderable
    {
        return view('laravel-maker::livewire.dashboard')->layout('laravel-maker::components.layouts.app');
    }
}
