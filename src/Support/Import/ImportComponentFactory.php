<?php

namespace Laltu\LaravelMaker\Html\Import;

class ImportComponentFactory
{
    private array $componentMappings = [
        'text' => 'TextInput',
        'email' => 'EmailInput',
        'select' => 'SelectInput',
        'file' => 'FileUpload',
        // Add more component mappings here if needed
    ];

    public function getImportComponent($type): ?ImportComponent
    {
        if (!isset($this->componentMappings[$type])) {
            return null;
        }
        $className = $this->componentMappings[$type];
        return new $className;
    }
}