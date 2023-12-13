<?php

namespace Laltu\LaravelMaker\Livewire;

use Livewire\Component;

class ListModule extends Component
{
    public $formRelationalFields = [];

    public $formFields = [];

    public function addFormFieldRow()
    {
        $this->formFields[] = ['name' => '', 'email' => ''];
    }

    public function removeFormFieldRow($index)
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields); // Re-index the array
    }

    public function addFormRelationalFieldRow()
    {
        $this->formRelationalFields[] = ['name' => '', 'email' => ''];
    }

    public function removeFormRelationalFieldRow($index)
    {
        unset($this->formRelationalFields[$index]);
        $this->formRelationalFields = array_values($this->formRelationalFields); // Re-index the array
    }

    public function render()
    {
        return view('laravel-maker::livewire.list-module');
    }
}