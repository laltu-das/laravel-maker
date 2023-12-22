<?php

namespace Laltu\LaravelMaker\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class SidePanel extends Component
{
    public bool $open = false;
    public string $title = 'Default Panel';
    public string $component = '';

    #[On('side-panel')]
    public function openPanel(string $title, string $component): void
    {
        $this->open = true;
        $this->title = $title;
        $this->component = $component;
    }

    public function render()
    {
        return view('laravel-maker::livewire.side-panel');
    }
}
