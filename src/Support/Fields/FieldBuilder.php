<?php

namespace Laltu\LaravelMaker\Support\Fields;

class FieldBuilder
{
    private RelationshipBuilder $relationshipBuilder;

    public function __construct(RelationshipBuilder $relationshipBuilder)
    {
        $this->relationshipBuilder = $relationshipBuilder;
    }

    public function buildFieldLine(FieldDefinition $fieldData): string
    {
        if ($fieldData->relationship) {
            $fieldLine = $this->relationshipBuilder->buildRelationshipFieldLine($fieldData->relationship);
        }else{
            $fieldLine = "\$table->{$fieldData['type']}('{$fieldData['name']}')";
        }

        if ($fieldData['nullable']) {
            $fieldLine .= '->nullable()';
        }

        if ($fieldData['unique']) {
            $fieldLine .= '->unique()';
        }

        if ($fieldData['default'] !== null) {
            // Careful with database differences when setting defaults
            $fieldLine .= "->default({$fieldData['default']})";
        }

        if ($fieldData['columnSize']) {
            // Assuming 'size' is a general guideline; databases might be specific
            $fieldLine .= "->size({$fieldData['columnSize']})";
        }

        if (isset($fieldData['unsigned']) && $fieldData['unsigned']) {
            $fieldLine .= '->unsigned()'; // Assuming your database supports an 'unsigned' modifier
        }

        if ($fieldData['options']) {
            // Assuming database support for 'enum' or similar
            $options = implode(',', array_map(function ($option) { return "'$option'"; }, $fieldData['options']));
            $fieldLine .= "->enum($options)";
        }

        return $fieldLine;
    }
}
