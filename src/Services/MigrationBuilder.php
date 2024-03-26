<?php

namespace Laltu\LaravelMaker\Services;

use Illuminate\Support\Collection;

class MigrationBuilder
{
    private Collection $fields;
    private FieldParser $fieldParser;

    public function __construct(string $fieldsString = null)
    {
        $this->fieldParser = new FieldParser();
        // Corrected to directly utilize the FieldParser's parseFields method
        $this->fields = $this->fieldParser->parseFields($fieldsString);
    }

    public function processFields(): string
    {
        return $this->fields->map(function ($fieldData) {
            return $this->isRelationship($fieldData)
                ? $this->buildRelationshipFieldLine($fieldData)
                : $this->buildFieldLine($fieldData);
        })->implode("\n            ");
    }

    public function buildFieldLine($fieldData): string
    {
        $fieldLine = "\$table->{$fieldData['type']}('{$fieldData['name']}')";

        if (!empty($fieldData['nullable'])) {
            $fieldLine .= '->nullable()';
        }

        if (!empty($fieldData['unique'])) {
            $fieldLine .= '->unique()';
        }

        if (isset($fieldData['default'])) {
            // Correct handling for string defaults
            $default = is_numeric($fieldData['default']) ? $fieldData['default'] : "'{$fieldData['default']}'";
            $fieldLine .= "->default({$default})";
        }

        // Removed the incorrect method ->size() since it's not a general method for columns in Laravel migrations
        if (!empty($fieldData['unsigned']) && $fieldData['type'] !== 'enum') {
            $fieldLine .= '->unsigned()';
        }

        if (!empty($fieldData['options']) && $fieldData['type'] === 'enum') {
            $options = implode(',', array_map(function ($option) { return "'$option'"; }, $fieldData['options']));
            $fieldLine .= "->enum('{$fieldData['name']}', [$options])";
        }

        return $fieldLine . ';';
    }

    public function buildRelationshipFieldLine($fieldData): string
    {
        // The original method's approach seemed inverted; this is simplified
        $relationshipDetails = $this->getRelationshipDetails($fieldData);
        if (empty($relationshipDetails)) {
            // Fallback or error handling if necessary
            return '';
        }

        // Here you would handle different relationship types accordingly.
        // This example assumes a simple foreignId relationship for illustration.
        $fieldLine = "\$table->foreignId('{$relationshipDetails['foreignKey']}')";
        $fieldLine .= "->constrained('{$relationshipDetails['relatedModel']}')";
        $fieldLine .= "->cascadeOnUpdate()->cascadeOnDelete();";

        return $fieldLine;
    }

    // This method seems to intend to provide relationship details for a field,
    // but it's currently not utilized properly. Adjusting it to fit the logic better.
    private function getRelationshipDetails($fieldData): array
    {
        // Initially check if the 'params' key exists and contains the required information
        if (!empty($fieldData['params'])) {
            // Assuming 'params' is a string like "tableName|foreignKey|localKey"
            $params = explode('|', $fieldData['params']);

            // Basic validation to ensure we have the expected parts
            if (count($params) >= 2) {
                $details = [
                    'relatedModel' => $params[0], // The table name of the related model
                    'foreignKey' => $params[1], // The foreign key in the relationship
                ];

                // If there's a third parameter, it's assumed to be the local key
                if (isset($params[2])) {
                    $details['localKey'] = $params[2];
                }

                return $details;
            }
        }

        // Return an empty array or possibly throw an exception if the relationship details are insufficient
        return [];
    }


    private function isRelationship($fieldData): bool
    {
        $validRelationships = ['hasOne', 'hasMany', 'belongsTo', 'belongsToMany', 'hasOneThrough', 'hasManyThrough', 'morphTo', 'morphOne', 'morphMany', 'morphToMany', 'morphedByMany'];

        // Check if the field data represents a valid Laravel relationship
        if (in_array($fieldData['type'], $validRelationships)) {
            return true;
        }

        return false;
    }
}
