<?php

namespace Laltu\LaravelMaker\Support;

use Illuminate\Support\Str;

class VueFormBuilder
{
    public string $model;
    public array $fields;
    public string $routeName;

    public function __construct($model, array $fields, $routeName)
    {
        $this->model = $model;
        $this->fields = $fields;
        $this->routeName = $routeName;
    }

    public function generateDynamicImports(): string
    {
        $imports = [
            "import AdminLayout from '@/Layouts/AppLayout.vue';",
            "import InputError from '@/Components/InputError.vue';",
            "import SubmitButton from '@/Components/Button/SubmitButton.vue';",
            "import CancelButton from '@/Components/Button/CancelButton.vue';",
        ];

        $componentMappings = $this->getFieldComponentMappings();

        foreach ($this->fields as $field) {
            if (isset($componentMappings[$field['type']])) {
                $imports[] = "import {$componentMappings[$field['type']]} from '@/Components/Form/{$componentMappings[$field['type']]}.vue';";
            }
        }

        return implode("\n", array_unique($imports));
    }

    public function getFieldComponentMappings(): array
    {
        return [
            'text' => 'TextInput',
            'email' => 'EmailInput',
            'select' => 'SelectInput',
            'file' => 'FilepondFileUpload',
            'radio' => 'RadioInput',
            'checkbox' => 'CheckboxInput',
        ];
    }

    public function generateFormTemplate(): string
    {
        return collect($this->fields)->map(function ($field) {
            $inputComponent = $this->generateInputComponent($field);
            $errorComponent = $this->generateErrorComponent($field['name']);
            return $this->generateFieldWrapper($inputComponent, $errorComponent);
        })->implode("\n                        ");
    }

    protected function generateInputComponent($field): string
    {
        return match ($field['type']) {
            'select' => $this->generateSelectComponent($field),
            'radio' => $this->generateRadioComponent($field),
            'checkbox' => $this->generateCheckboxComponent($field),
            'file' => $this->generateFilepondComponent($field),
            default => $this->generateDefaultInputComponent($field),
        };
    }

    protected function generateFilepondComponent($field): string
    {
        // Assume $field['name'] is the model attribute to bind the uploaded file's server ID
        $modelName = "form." . $field['name'];

        // Construct the FilePond file upload component
        // Add additional props and event listeners as needed
        return "<FilepondFileUpload name=\"{$field['name']}\" ref=\"filepondImageInput\" @processfile=\"handleFilePondImageProcess\" @removefile=\"handleFilePondImageRemoveFile\" v-model=\"{$modelName}\" />";
    }

    protected function generateDefaultInputComponent($field): string
    {
        $label = ucfirst($field['name']);
        $componentTag = $this->getFieldComponentTag($field['type']);
        $modelName = "form." . $field['name'];
        $inputType = "type=\"{$field['type']}\"";

        return "<{$componentTag} v-model=\"{$modelName}\" :label=\"'{$label}'\" {$inputType} />";
    }

    protected function generateSelectComponent($field): string
    {
        $label = ucfirst($field['name']);
        $modelName = "form." . $field['name'];
        $optionsString = $this->prepareOptionsString($field);

        return "<SelectInput v-model=\"{$modelName}\" :label=\"'{$label}'\" {$optionsString} />";
    }

    protected function generateRadioComponent($field): string
    {
        // Example for a single radio button, extend for multiple
        $label = ucfirst($field['name']);
        $modelName = "form." . $field['name'];

        // Assuming options are provided for radio inputs
        $optionsString = $this->prepareOptionsString($field);

        return "<RadioInput v-model=\"{$modelName}\" :label=\"'{$label}'\" :options=\"{$optionsString}\" />";
    }

    protected function generateCheckboxComponent($field): string
    {
        $label = ucfirst($field['name']);
        $modelName = "form." . $field['name'];

        return "<CheckboxInput v-model=\"{$modelName}\" :label=\"'{$label}'\" />";
    }

    protected function generateErrorComponent($fieldName): string
    {
        $errorMessage = "form.errors." . $fieldName;

        return "<InputError :message=\"{$errorMessage}\" class=\"mt-2\"/>";
    }


    protected function generateFieldWrapper($inputComponent, $errorComponent): string
    {
        $tab = str_repeat(" ", 28); // For the input and error Components
        $closingTab = str_repeat(" ", 24); // For the closing </div> tag

        return "<div class=\"mb-4\">\n{$tab}{$inputComponent}\n{$tab}{$errorComponent}\n{$closingTab}</div>";
    }


    public function generateFormData(string $modelName): string
    {
        // Start the formData definition with a computed property
        $formDataScript = "const formData = computed(() => ({\n";

        foreach ($this->fields as $field) {
            $defaultValue = match ($field['type']) {
                'checkbox' => 'false',
                'file' => "null",
                default => "''", // Use empty string for text-like inputs by default
            };

            // Use the provided model name dynamically in the generated script
            $formDataScript .= "    {$field['name']}: isEditMode.value ? ({$modelName}.{$field['name']} ?? {$defaultValue}) : {$defaultValue},\n";
        }

        // Close the formData definition
        $formDataScript .= "}));\n";

        return $formDataScript;
    }

    public function generateWatchFormData(string $modelName): string
    {
        // Start the watch function to react to changes in the model
        $watchScript = "watch(() => $modelName, (new$modelName) => {\n";

        foreach ($this->fields as $field) {
            $defaultValue = match ($field['type']) {
                'checkbox' => 'false',
                'file' => "null",
                default => "''", // Use empty string for text-like inputs by default
            };

            // Dynamically update formData based on the model changes
            $watchScript .= "    formData.{$field['name']} = new$modelName ? (newModel.{$field['name']} ?? {$defaultValue}) : {$defaultValue};\n";
        }

        // Close the watch function
        $watchScript .= "}, { deep: true, immediate: true });\n";

        return $watchScript;
    }


    protected function prepareOptionsString($field): string
    {
        if (!empty($field['options']) && in_array($field['type'], ['select', 'radio'])) {
            $options = json_encode($field['options']);
            return ":options='{$options}'";
        }
        return "";
    }

    public function getFieldComponentTag($type): string
    {
        $mappings = $this->getFieldComponentMappings();

        return $mappings[$type] ?? 'TextInput';
    }

    public function generateScriptTemplate(): string
    {
        // Check if there is a field of type 'file' in the fields array
        $hasFileInput = collect($this->fields)->contains(function ($field) {
            return $field['type'] === 'file';
        });

        // Choose the stub file based on whether a file input exists
        $stubFileName = $hasFileInput ? 'form-script-file.stub' : 'form-script.stub';
        $fieldStubContent = file_get_contents($this->resolveStubPath("/stubs/inertia/vue/{$stubFileName}"));

        // Define replacements for placeholders in the stub
        $replacements = [
            '{{routeName}}' => $this->routeName,
            '{{modelNameCamel}}' => Str::camel($this->model),
            '{{formTemplate}}' => $this->generateFormTemplate(),
            '{{dynamicImports}}' => $this->generateDynamicImports(),
            '{{formData}}' => $this->generateFormData($this->model),
            '{{watchFormData}}' => $this->generateWatchFormData($this->model),
        ];

        // Perform the replacements and return the modified content
        return str_replace(array_keys($replacements), array_values($replacements), $fieldStubContent);
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
}
