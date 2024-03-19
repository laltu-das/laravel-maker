<?php

namespace Laltu\LaravelMaker\Support\Import;

class DynamicImportGenerator
{
    private ?DynamicImportGenerator $instance = null;
    private ImportManager $importManager;

    private function __construct()
    {
        $this->importManager = new ImportManager();
    }

    public function getInstance(): ?DynamicImportGenerator
    {
        if (null === $this->instance) {
            $this->instance = new self;
        }
        return $this->instance;
    }

    public function generate(array $fields): string
    {
        $allImports = array_merge(
            $this->getDefaultImports(),
            $this->getDynamicImports('Components/Form', $fields),
            $this->getDynamicImports('Components/Table', $fields)
        );
        return implode("\n", $allImports);
    }

    protected function getDefaultImports(): array
    {
        $inertiaImports = (new InertiaImportsManager())->getImports();
        $customComponentsImports = (new CustomComponentsImportsManager())->getImports();

        return array_merge(
            $inertiaImports,
            $customComponentsImports,
            [
                // Add more default imports here if needed
            ]
        );
    }

    protected function getDynamicImports($basePath, array $fields): array
    {
        $imports = [];
        foreach ($fields as $field) {
            $component = (new ImportComponentFactory())->getImportComponent($field['type']);
            if (null === $component) {
                continue;
            }
            $imports[] = $component->getImportStatement($basePath);
        }
        return array_unique($imports);
    }

    public function addDefaultImports(array $imports): void
    {
        $currentImports = $this->importManager->getImports();
        $mergedImports = array_merge($currentImports, $imports);
        $this->importManager->setImports($mergedImports);
    }
}