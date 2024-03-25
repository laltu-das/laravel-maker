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

    public function addFieldRow()
    {
        $this->form->addFieldRow();
    }

    public function addRelationshipFieldRow()
    {
        $this->form->addRelationshipFieldRow();
    }

    public function removeFormFieldRow(int $index)
    {
        $this->form->removeFormFieldRow($index);
    }

    public function save()
    {
        $this->form->update();

        return redirect()->route('laravel-maker.schema');
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema-create')->layout('laravel-maker::components.layouts.app');
    }
}
