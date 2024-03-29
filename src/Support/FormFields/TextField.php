<?php

namespace Laltu\LaravelMaker\Support\FormFields;

class TextField extends FieldGenerator
{
    public function generate(): string
    {
        $name = $this->field['name'];
        $default = $this->field['default'] ?? '';
        return "<input type=\"text\" id=\"$name\" name=\"$name\" value=\"$default\" class=\"form-control\" />\n";
    }
}