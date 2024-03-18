<?php

namespace Laltu\LaravelMaker\Html\Import;

class ImportManager
{
    private array $imports = [];

    public function getImports(): array
    {
        return $this->imports;
    }

    public function addImport(string $import): void
    {
        $this->imports[] = $import;
    }

    public function setImports(array $imports): void
    {
        $this->imports = $imports;
    }

    public function addImportWithParameters(string $import): void
    {
        $this->imports[] = "import {$import};";
    }
}