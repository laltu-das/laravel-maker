<?php

namespace Laltu\LaravelMaker\Html\Import;

class ImportComponent
{
    protected $type;

    protected function __construct($type)
    {
        $this->type = $type;
    }

    public function getImportStatement($basePath): string
    {
        return "import {$this->type} from '@/{$basePath}/{$this->type}.vue';";
    }
}