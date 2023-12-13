<?php

namespace Laltu\LaravelMaker\Livewire;

use Livewire\Component;

class FormBuilder extends Component
{
    public $formFields = [];
    public $formRelationalFields = [];

    protected $listeners = ['fieldOrderUpdated'];

    public function mount()
    {
        $this->formFields = [
            ['id' => 1, 'label' => 'Field 1'],
            ['id' => 2, 'label' => 'Field 2'],
        ];
    }

    public function updateOrder($list)
    {
        $this->formFields = collect($list)->map(function ($item, $index) {
            return ['id' => $item['id'], 'label' => $item['innerText']];
        })->toArray();
    }

    public function fieldOrderUpdated($list)
    {
        $this->updateOrder($list);
    }

    public function render()
    {
        return view('laravel-maker::livewire.form-builder');
    }
}