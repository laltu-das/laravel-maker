<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\Livewire\Forms\SchemaForm;
use Laltu\LaravelMaker\Models\Schema;

use Livewire\Component;

class SchemaCreate extends Component
{
    public SchemaForm $form;

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
        $this->validate();

        Schema::create(
            $this->form->all()
        );

        return redirect()->route('schema');
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema-create')->layout('laravel-maker::components.layouts.app');
    }
}