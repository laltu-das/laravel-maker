<?php

namespace Laltu\LaravelMaker\Html\Fields;

class ButtonField extends FieldGenerator
{
    public function generate(): string
    {
        $name = $this->field['name'];
        return "<input type=\"button\" value=\"" . ucfirst($name) . "\" class=\"btn btn-primary\" />\n";
    }
}