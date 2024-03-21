<?php

namespace Laltu\LaravelMaker\Support\Fields;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RelationshipBuilder
{
    private Collection $relations;

    public function __construct(string $relationsString = null)
    {
        $this->relations = $this->parseRelations($relationsString);
    }

    public function buildRelationMethods(): string
    {
        return $this->relations->filter(function ($details) {
            return isset($details['name'], $details['type'], $details['params']);
        })
            ->map(function ($details) {
                return $this->buildRelationMethod($details);
            })
            ->implode(PHP_EOL . PHP_EOL); // Adds newlines for readability
    }

    private function buildRelationMethod(array $details): string
    {
        [$relatedModel, $foreignKey, $ownerKey] = array_pad(explode('|', $details['params']), 3, null);

        $methodBody = "\$this->{$details['type']}($relatedModel::class";
        if ($foreignKey) {
            $methodBody .= ", '$foreignKey'";
            if ($ownerKey) {
                $methodBody .= ", '$ownerKey'";
            }
        }
        $methodBody .= ');';

        return str_replace(['{{ method_name }}', '{{ method_body }}'], [$details['name'], $methodBody], $this->getMethodStub());
    }

    private function parseRelations(string $relationsString = null): Collection
    {
        if (!$relationsString) {
            return collect();
        }

        return collect(explode(';', $relationsString))
            ->map(function ($relation) {
                return collect(explode(',', trim($relation)))
                    ->mapWithKeys(function ($part) {
                        [$key, $value] = explode(':', $part);
                        return [trim($key) => trim($value)];
                    });
            });
    }

    private function getMethodStub(): string
    {
        // This would read from a stub file. For illustration, I'll provide a string:
        return <<<STUB
public function {{ method_name }}() 
{
    {{ method_body }}
}
STUB;
    }

    public function buildRelationshipFieldLine(array $relationshipDetails): string
    {
        if (!in_array($relationshipDetails['type'], ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 'hasOneThrough', 'hasManyThrough'])) {
            $fieldLine = "\$table->foreignId('{$relationshipDetails['foreignKey']}')";

            // Optional: Include a constraint name if desired
            $fieldLine .= "->constrained('{$relationshipDetails['relatedModel']}')";

            $fieldLine .= "->cascadeOnUpdate()->cascadeOnDelete()";

            return $fieldLine;
        }else{
            return $this->buildMorphFieldLine($relationshipDetails);
        }
    }

    private function buildMorphFieldLine(array $relationshipDetails): string
    {
        // Assumptions:
        // * Standard Laravel column naming conventions: "[name]_type", "[name]_id"

        $name = $relationshipDetails['params'][0] ?? $relationshipDetails['name']; // If no explicit name in params

        return "\$table->morphs('{$name}');";
    }

}
