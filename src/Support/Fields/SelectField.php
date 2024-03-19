<?php

namespace Laltu\LaravelMaker\Support\Fields;

class SelectField extends FieldGenerator
{
    public function generate()
    {
        $name = $this->field['name'];
        $options = $this->field['options'] ?? [];
        $selectHtml = "<select id=\"$name\" name=\"$name\">\n";
        foreach ($options as $optionValue => $optionDisplay) {
            $selectHtml .= "    <option value=\"$optionValue\">$optionDisplay</option>\n";
        }
        $selectHtml .= "</select>\n";
        return $selectHtml;
    }
}