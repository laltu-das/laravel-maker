<?php

namespace Laltu\LaravelMaker\Livewire\Forms;
use Livewire\Attributes\Validate;
use Livewire\Form;
class SchemaForm extends Form
{
    #[Validate('required|min:5')]
    public $title = '';

    #[Validate('required|min:5')]
    public $content = '';
}