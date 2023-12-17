<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Contracts\Support\Renderable;
use Laltu\LaravelMaker\View\Components\AppLayout;
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
        return view('laravel-maker::livewire.servers')->layout(AppLayout::class);
    }
}
