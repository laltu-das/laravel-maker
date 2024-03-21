<?php

namespace Laltu\LaravelMaker\Support\Fields;

class RelationshipDetails
{
    public function __construct(
        public string $type,         // 'hasOne', 'hasMany', 'belongsTo', etc.
        public string $relatedModel,
        public string $foreignKey,
        public string $localKey = 'id' // Optional
    ) {}
}