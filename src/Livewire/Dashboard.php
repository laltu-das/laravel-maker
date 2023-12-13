<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Component;

class Dashboard extends Component
{
    public $activeTab = 'tab1';

    public function switchTab($tab): void
    {
        $this->activeTab = $tab;
    }

    public function getTabContentProperty(): string
    {
        // Define the content for each tab
        if ($this->activeTab === 'tab1') {
            return 'Content for Tab 1';
        } elseif ($this->activeTab === 'tab2') {
            return 'Content for Tab 2';
        } else {
            return 'Default Content';
        }
    }

    /**
     * Render the component.
     */
    public function render(): Renderable
    {
        return view('laravel-maker::livewire.dashboard', [
            'servers' => collect(),
            'time' => collect(),
            'runAt' => collect(),
        ]);
    }
}
