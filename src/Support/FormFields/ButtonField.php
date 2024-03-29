<?php

namespace Laltu\LaravelMaker\Support\FormFields;

class ButtonField extends FieldGenerator
{
    public function generate(): string
    {
        $name = $this->field['name'];
        return "<input type=\"button\" value=\"" . ucfirst($name) . "\" class=\"btn btn-primary\" />\n";
    }
}