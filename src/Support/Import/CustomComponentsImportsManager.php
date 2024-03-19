<?php

namespace Laltu\LaravelMaker\Support\Import;

class CustomComponentsImportsManager extends ImportManager
{
    public function addDefaultCustomComponentImports(): void
    {
        $this->addImportWithParameters("AdminLayout from '@/Layouts/AdminLayout.vue'");
        $this->addImportWithParameters("InputError from '@/Components/InputError.vue'");
        // Add more custom component imports here
    }
}