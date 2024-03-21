<?php

namespace Laltu\LaravelMaker\Support\Fields;

class FieldDefinition
{
    public function __construct(
        public string $name,
        public string $type,
        public array $options = [],
        public bool $nullable = false,
        public bool $unique = false,
        public mixed $default = null,
        public ?int $columnSize = null,
        public ?RelationshipDetails $relationship = null
    ) {}
}
