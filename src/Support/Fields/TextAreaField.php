<?php

namespace Laltu\LaravelMaker\Support\Fields;

class TextAreaField extends FieldGenerator
{
    public function generate()
    {
        $name = $this->field['name'];
        $default = $this->field['default'] ?? '';
        $rows = $this->field['rows'] ?? 4;
        $cols = $this->field['cols'] ?? 50;
        return "<textarea id=\"$name\" name=\"$name\" rows=\"$rows\" cols=\"$cols\">$default</textarea>\n";
    }
}