<?php

namespace Laltu\LaravelMaker\Support;

class FieldParser
{

    public function parseFieldsToArray(string $fieldsString): array
    {
        return collect(explode(',', $fieldsString))
            ->map(function ($fieldDefinition) {
                return $this->parseFieldDefinition($fieldDefinition);
            })
            ->toArray();
    }

    public function processFields(string $fieldsString): string
    {
        return collect($this->parseFieldsToArray($fieldsString))
            ->map(function ($fieldData) {
                return $this->buildFieldLine($fieldData);
            })
            ->implode("            \n");
    }

    private function parseFieldDefinition(string $fieldDefinition): array
    {
        return collect(explode(';', $fieldDefinition))
            ->mapWithKeys(function ($part) {
                [$key, $value] = explode(':', $part);
                return [trim($key) => trim($value)];
            })
            ->toArray();
    }

    private function buildFieldLine(array $fieldData): string
    {

        $relationshipDetails = $this->getRelationshipDetails($fieldData);
        if ($relationshipDetails) {
            $fieldLine = $this->buildRelationshipFieldLine($relationshipDetails);
        }else{
            $fieldLine = "\$table->{$fieldData['type']}('{$fieldData['name']}')";
        }

        if ($this->shouldBeNullable($fieldData)) {
            $fieldLine .= "->nullable()";
        }

        if ($this->shouldBeUnique($fieldData)) {
            $fieldLine .= "->unique()";
        }

        if (isset($fieldData['default'])) {
            $fieldLine .= "->default({$fieldData['default']})";
        }

        if (isset($fieldData['col'])) {
            $fieldLine .= "->size({$fieldData['col']})";
        }

        if (isset($fieldData['options'])) {
            $fieldLine .= "->enum(" . implode(',', explode('|', $fieldData['options'])) . ")";
        }

        return $fieldLine . ';';
    }

    private function buildRelationshipFieldLine(array $relationshipDetails): string
    {
        $fieldLine = "\$table->foreignId('{$relationshipDetails['foreignKey']}')";

        // Optional: Include a constraint name if desired
        $fieldLine .= "->constrained('{$relationshipDetails['relatedModel']}')";

        $fieldLine .= "->cascadeOnUpdate()->cascadeOnDelete()";

        return $fieldLine;
    }

    private function getRelationshipDetails(array $field): array
    {
        if (!in_array($field['type'], ['hasOne', 'hasMany', 'belongsTo', 'hasOneThrough', 'hasManyThrough'])) {
            return [];
        }

        $params = explode('|', $field['params']);
        return [
            'type' => $field['type'],
            'relatedModel' => $params[0],
            'foreignKey' => $params[1],
        ];
    }

    private function shouldBeNullable(array $fieldData): bool
    {
        return isset($fieldData['default']) && $fieldData['default'] === 'null';
    }

    private function shouldBeUnique(array $fieldData): bool
    {
        return isset($fieldData['unique']) && $fieldData['unique'] === 'true';
    }
}
