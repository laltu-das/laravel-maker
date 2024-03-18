<?php

namespace Laltu\LaravelMaker\Html\Fields;

class ImageField extends FieldGenerator
{
    public function generate()
    {
        $name = $this->field['name'];
        $src = $this->field['src'] ?? 'path-to-image.jpg'; // Default image path
        return "<input type=\"image\" id=\"$name\" name=\"$name\" src=\"$src\" alt=\"" . ucfirst($name) . "\" />\n";
    }
}