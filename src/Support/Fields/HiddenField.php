<?php

namespace Laltu\LaravelMaker\Support\Fields;

class HiddenField extends FieldGenerator
{
    public function generate()
    {
        $name = $this->field['name'];
        $default = $this->field['default'] ?? '';
        return "<input type=\"hidden\" id=\"$name\" name=\"$name\" value=\"$default\">\n";
    }
}