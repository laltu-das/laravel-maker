<?php

namespace Laltu\LaravelMaker\Services;

use Illuminate\Support\Collection;

class ModelBuilder
{
    private Collection $relations;
    private Collection $fields;
    private FieldParser $fieldParser;

    public function __construct(string $fieldsString = null, string $relationsString = null)
    {
        $this->fieldParser = new FieldParser();

        // Instantiated FieldParser object to parse fields and relations
        if ($fieldsString){
            $this->fields = collect($this->fieldParser->parseFields($fieldsString));
        }

        if ($relationsString){
            $this->relations = collect($this->fieldParser->parseRelationships($relationsString));
        }
    }

    public function buildFillableFields(): string
    {
        return $this->fields
            ->map(function ($field) {
                return "'" . $field['name'] . "'";
            })
            ->implode(', ');
    }

    public function buildGuardedFields(): array
    {
        $fillableFields = explode(', ', $this->buildFillableFields());

        return $this->fields
            ->pluck('name')
            ->reject(function ($fieldName) use ($fillableFields) {
                return in_array("'" . $fieldName . "'", $fillableFields);
            })
            ->values()
            ->toArray();
    }

    public function buildCasts(): string
    {
        return $this->fields
            ->map(function ($attribute) {
                return "'".$attribute['name']."' => '".$attribute['type']."'";
            })->implode(', ');
    }

    public function buildRelationMethods(): string
    {
        return $this->relations
            ->map(function ($details) {
                return $this->buildRelationMethod($details);
            })
            ->implode(PHP_EOL . PHP_EOL);
    }

    private function buildRelationMethod($details): string
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

        return $this->generateMethodStub($details['name'], $methodBody);
    }

    private function generateMethodStub(string $methodName, string $methodBody): string
    {
        return <<<STUB
    public function $methodName()
    {
        $methodBody
    }
    STUB;
    }
}
