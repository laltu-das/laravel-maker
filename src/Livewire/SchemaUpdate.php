<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Livewire\Forms\SchemaForm;
use Laltu\LaravelMaker\Models\Schema;
use Livewire\Component;

class SchemaUpdate extends Component
{
    public SchemaForm $form;

    public function mount(Schema $schema)
    {
        $this->form->setSchema($schema);
    }

    public function save()
    {
        $this->form->update();

        return $this->redirect('/posts');
    }

    public function render()
    {
        return view('livewire.schema-update')->layout('laravel-maker::components.layouts.app');
    }
}
