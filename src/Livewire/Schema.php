<?php

namespace Laltu\LaravelMaker\Livewire;

use Livewire\Component;

class Schema extends Component
{
    public $count = 1;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema');
    }
}