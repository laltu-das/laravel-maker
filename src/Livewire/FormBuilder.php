<?php

namespace Laltu\LaravelMaker\Livewire;

use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class FormBuilder extends Component
{
    public array $formFields = [];
    public array $formRelationalFields = [];

    protected $listeners = ['fieldOrderUpdated'];

    public function mount(): void
    {
        $this->formFields = [
            ['id' => 1, 'label' => 'Field 1'],
            ['id' => 2, 'label' => 'Field 2'],
        ];
    }

    public function updateOrder($list): void
    {
        $this->formFields = collect($list)->map(function ($item, $index) {
            return ['id' => $item['id'], 'label' => $item['innerText']];
        })->toArray();
    }

    public function fieldOrderUpdated($list): void
    {
        $this->updateOrder($list);
    }

    public function render()
    {
        return view('laravel-maker::livewire.form-builder')->layout(AppLayout::class);
    }
}