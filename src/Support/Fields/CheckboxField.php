<?php

namespace Laltu\LaravelMaker\Support\Fields;

class CheckboxField extends FieldGenerator
{
    public function generate(): string
    {
        $name = $this->field['name'];
        $default = $this->field['default'] ?? '';
        return "<input type=\"checkbox\" id=\"$name\" name=\"$name\" value=\"$default\">\n<label for=\"$name\">" . ucfirst($name) . "</label>\n";
    }
}