<?php

namespace Laltu\LaravelMaker\Support;

use Illuminate\Support\Str;

trait VueTemplateBuilder
{
    protected function generateScriptSetupContent(): string
    {
        $replacements = [
            '{{dynamicImports}}' => $this->generateDynamicImports(),
            '{{modelNameCamel}}' => Str::camel($this->model),
            '{{formFields}}' => $this->generateFormFields(),
        ];

        return $this->processStub('/stubs/vue/script-setup.stub', $replacements);
    }

    protected function generateHeaderTemplate(): string
    {
        $replacements = [
            '{{pageTitle}}' => $this->pageTitle,
            '{{routeName}}' => $this->routeName,
            '{{modelNameCamel}}' => Str::camel($this->model),
            '{{formTemplate}}' => $this->generateFormTemplate(),
        ];

        return $this->processStub('/stubs/vue/component-header.stub', $replacements);
    }

    protected function generateDynamicImports(): string
    {
        $imports = [
            "import { useForm, usePage } from '@inertiajs/vue3';",
            "import AdminLayout from '@/Layouts/AppLayout.vue';",
            "import InputError from '@/Components/InputError.vue';",
            "import SubmitButton from '@/Components/Buttons/SubmitButton.vue';",
            "import CancelButton from '@/Components/Buttons/CancelButton.vue';",
        ];

        $componentMappings = $this->getFieldComponentMappings();

        foreach ($this->fields as $field) {
            if (isset($componentMappings[$field['type']])) {
                $imports[] = "import {$componentMappings[$field['type']]} from '@/Components/Form/{$componentMappings[$field['type']]}.vue';";
            }
        }

        return implode("\n", array_unique($imports));
    }

    protected function getFieldComponentMappings(): array
    {
        return [
            'text' => 'TextInput',
            'email' => 'EmailInput',
            'select' => 'SelectInput',
            'file' => 'FileUpload',
        ];
    }

    protected function generateFormFields(): string
    {
        // Assuming $this->model contains the model name, e.g., 'banner'
        $modelNameCamel = Str::camel($this->model); // Convert model name to camelCase if it isn't already

        return collect($this->fields)->map(function ($field) use ($modelNameCamel) {
            // Construct each line with proper indentation
            return "{$field['name']}: $modelNameCamel.{$field['name']} ?? '',";
        })->implode("\n    ");
    }

    protected function generateFormTemplate(): string
    {
        return collect($this->fields)->map(function ($field) {
            $fieldStubContent = file_get_contents($this->resolveStubPath('/stubs/vue/form-field.stub'));

            $label = ucfirst($field['name']); // Capitalize the first letter of the label
            $componentTag = $this->getFieldComponentTag($field['type']);
            $modelName = "form." . $field['name'];
            $errorMessage = "form.errors." . $field['name'];

            // Check if the field type is 'select' to conditionally include the type attribute
            $inputType = $field['type'] === 'select' ? '' : "type=\"{$field['type']}\"";

            // Prepare the option string if applicable
            $optionsString = $this->prepareOptionsString($field);

            // Assemble the replacement array, conditionally including inputType
            $replacements = [
                '{{componentTag}}' => $componentTag,
                '{{modelName}}' => $modelName,
                '{{label}}' => $label,
                '{{inputType}}' => $inputType, // This is now a conditional string
                '{{optionsPlaceholder}}' => $optionsString,
                '{{errorMessage}}' => $errorMessage,
            ];

            // Perform the replacements for each field individually
            $replacedContent = str_replace(array_keys($replacements), array_values($replacements), $fieldStubContent);

            // Optionally remove any empty lines that may result from missing attributes
            return preg_replace("/^\s*[\r\n]/m", '', $replacedContent);
        })->implode("\n");
    }

    protected function prepareOptionsString($field): string
    {
        // Check if 'options' are provided for select fields or similar
        if (!empty($field['options']) && $field['type'] === 'select') {
            $options = json_encode($field['options']);
            return ":options='{$options}'"; // Adjust Vue syntax as needed
        }
        return ""; // Return empty string if no options are needed
    }


    protected function getFieldComponentTag($type): string
    {
        $mappings = $this->getFieldComponentMappings();
        return $mappings[$type] ?? 'TextInput'; // Default to TextInput if a type is not mapped
    }

    /**
     * Resolve the fully qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = dirname(__FILE__, 3).$stub) ? $customPath : __DIR__ . $stub;
    }

    protected function processStub(string $stubName, array $replacements): string
    {
        $stubPath = $this->resolveStubPath($stubName);
        $content = file_get_contents($stubPath);

        foreach ($replacements as $placeholder => $replacement) {
            // Check if the replacement is an array and convert it to a string if necessary
            if (is_array($replacement)) {
                $replacement = implode("\n", $replacement); // Convert an array to string by joining elements with a newline
            }

            $content = str_replace($placeholder, $replacement, $content);
        }

        return $content;
    }


}
