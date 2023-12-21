<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Models\Schema;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class SchemaList extends Component
{
    public function render()
    {
        $schemas = Schema::get();

        return view('laravel-maker::livewire.schema-list', compact('schemas'))->layout(AppLayout::class);
    }
}