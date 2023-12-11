<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Contracts\Support\Renderable;
use Livewire\Attributes\Lazy;
use Livewire\Component;

/**
 * @internal
 */
#[Lazy]
class Servers extends Component
{

    /**
     * Render the component.
     */
    public function render(): Renderable
    {
        return view('laravel-maker::livewire.servers', [
            'servers' => collect(),
            'time' => collect(),
            'runAt' => collect(),
        ]);
    }
}
