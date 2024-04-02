<?php

namespace Laltu\LaravelMaker\Livewire\Schema;

use Laltu\LaravelMaker\Models\Schema;
use Livewire\Component;

class SchemaList extends Component
{
    public function render()
    {
        $schemas = Schema::paginate();

        return view('laravel-maker::livewire.schema.schema-list', compact('schemas'))->layout('laravel-maker::components.layouts.app');
    }

    public function deleteSchema(Schema $schema): void
    {
        $schema->delete();
    }
}