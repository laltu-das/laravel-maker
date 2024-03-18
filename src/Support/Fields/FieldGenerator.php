<?php

namespace Laltu\LaravelMaker\Html\Fields;

abstract class FieldGenerator
{
    protected $field;

    public function __construct($field)
    {
        $this->field = $field;
    }

    abstract public function generate();
}