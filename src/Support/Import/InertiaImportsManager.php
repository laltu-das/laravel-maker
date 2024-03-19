<?php

namespace Laltu\LaravelMaker\Support\Import;

class InertiaImportsManager extends ImportManager
{
    public function addDefaultInertiaImports(): void
    {
        $this->addImportWithParameters("{ useForm, usePage } from '@inertiajs/vue3'");
        $this->addImportWithParameters("InertiaModal from 'vue3-inertia-modal'");
        // Add more Inertia.js related imports here
    }
}