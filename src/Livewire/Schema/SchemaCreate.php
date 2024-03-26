<?php

namespace Laltu\LaravelMaker\Livewire\Schema;

use Laltu\LaravelMaker\Livewire\Forms\SchemaForm;
use Livewire\Component;

class SchemaCreate extends Component
{
    public SchemaForm $form;

    public function addFieldRow(): void
    {
        $this->form->addFieldRow();
    }

    public function addRelationshipFieldRow(): void
    {
        $this->form->addRelationshipFieldRow();
    }

    public function removeFormFieldRow(int $index): void
    {
        $this->form->removeFormFieldRow($index);
    }

    public function save()
    {
        $this->form->store();

        session()->flash('message', 'Schema created successfully.');

        return redirect()->route('laravel-maker.schema');
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema.schema-form')->layout('laravel-maker::components.layouts.app');
    }
}