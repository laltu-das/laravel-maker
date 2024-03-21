<?php

namespace Laltu\LaravelMaker\Support\Fields;

class FieldParser
{
    public function parseFieldsToArray(string $fieldsString): array
    {
        return array_map(function ($fieldDefinition) {
            return new FieldDefinition($this->processFieldParts(explode(';', $fieldDefinition)));
        }, explode(',', $fieldsString));
    }

    private function processFieldParts(array $fieldParts): array
    {
        $fieldData = $this->getDefaultFieldData();

        foreach ($fieldParts as $part) {
            [$key, $value] = explode(':', $part);
            $key = trim($key);
            $value = trim($value);

            switch ($key) {
                case 'name':
                case 'type':
                case 'nullable':
                case 'unique':
                case 'default':
                case 'size':
                case 'options':
                    $fieldData[$key] = $value;
                    break;
                case 'unsignedInteger':
                    $fieldData['type'] = 'integer';
                    $fieldData['unsigned'] = true;
                    break;
                case 'unsignedBigInteger':
                    $fieldData['type'] = 'bigInteger';
                    $fieldData['unsigned'] = true;
                    break;
                case 'relationship':
                    $fieldData['relationship'] = $this->parseRelationship($value);
                    break;
            }
        }

        return $fieldData;
    }

    private function getDefaultFieldData(): array
    {
        return [
            'name' => '',
            'type' => 'string',
            'options' => [],
            'nullable' => false,
            'unique' => false,
            'default' => null,
            'size' => null,
            'relationship' => null,
        ];
    }

    private function parseRelationship(string $relationshipString): RelationshipDetails
    {
        $params = explode('|', $relationshipString);
        return new RelationshipDetails(
            type: $params[0],
            relatedModel: $params[1],
            foreignKey: $params[2],
            localKey: $params[3] ?? 'id'
        );
    }
}