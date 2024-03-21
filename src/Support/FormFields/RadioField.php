<?php

namespace Laltu\LaravelMaker\Support\FormFields;

class RadioField extends FieldGenerator
{
    public function generate()
    {
        $name = $this->field['name'];
        $default = $this->field['default'] ?? '';
        return "<input type=\"radio\" id=\"$name\" name=\"$name\" value=\"$default\">\n<label for=\"$name\">" . ucfirst($name) . "</label>\n";
    }
}