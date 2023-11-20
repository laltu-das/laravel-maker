<?php

namespace Laltu\LaravelMaker\Livewire;

use Livewire\Component;

class CreatePost extends Component
{
    public function render()
    {
        return view('laravel-maker::livewire.create-post')->layout('laravel-maker::layout');
    }
}