<?php

namespace Laltu\LaravelMaker\Services;

use Illuminate\Support\Collection;

class FieldParser
{
    public function parseFields(string $fieldsString): Collection
    {
        return $this->parseDefinitions($fieldsString);
    }

    public function parseRelationships(string $relationshipsString): Collection
    {
        return $this->parseDefinitions($relationshipsString);
    }

    private function parseDefinitions(string $definitionsString): Collection
    {
        $secondaryDelimiter = ';';

        return collect(explode(',', $definitionsString))
            ->map(function ($definition) use ($secondaryDelimiter) {
                return collect(explode($secondaryDelimiter, $definition))
                    ->mapWithKeys(function ($part) {
                        [$key, $value] = explode(':', $part, 2);
                        return [trim($key) => trim($value)];
                    });
            });
    }
}
