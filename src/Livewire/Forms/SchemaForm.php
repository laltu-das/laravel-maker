<?php

namespace Laltu\LaravelMaker\Livewire\Forms;

use Laltu\LaravelMaker\Models\Schema;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SchemaForm extends Form
{
    public ?Schema $schema;

    #[Validate('required|min:5')]
    public $modelName = '';

    public array $fields = [[
        "fieldType" => '',
        "fieldName" => "",
        "dataType" => "",
        "validation" => "",
        "searchable" => false,
        "fillable" => false,
        "relationName" => "",
        "foreignModelOptions" => [],
        "foreignModel" => "",
        "foreignKey" => "",
        "localKey" => "",
        "relationType" => "",
        "nullable" => false,
    ]];

    public array $relationshipOptions = [
        "increments" => "Increments",
        "integer" => "Integer",
    ];

    public array $dataTypeOptions = [
        "increments" => "Increments",
        "integer" => "Integer",
        "smallInteger" => "SmallInteger",
        "longText" => "LongText",
        "bigInteger" => "BigInteger",
        "double" => "Double",
        "float" => "Float",
        "decimal" => "Decimal",
        "boolean" => "Boolean",
        "string" => "String",
        "char" => "Char",
        "text" => "Text",
        "json" => "Json",
        "mediumText" => "MediumText",
        "enum" => "Enum",
        "binary" => "Binary",
        "dateTime" => "DateTime",
        "date" => "Date",
        "timestamp" => "Timestamp",
    ];

    public function rules(): array
    {
        return [
            'modelName' => 'required|string|max:255',
            'fields.*.fieldType' => 'required|string',
            'fields.*.fieldName' => 'required|string',
            'fields.*.dataType' => 'required|string',
        ];
    }

    public function addFieldRow(): void
    {
        $this->fields[] = $this->emptyField() + ['id' => uniqid()];
    }

    protected function emptyField($field_type = ''): array
    {
        return [
            'id' => uniqid(),
            "fieldType" => $field_type,
            "fieldName" => "",
            "dataType" => "",
            "validation" => "",
            "searchable" => false,
            "fillable" => false,
            "relationName" => "",
            "foreignModelOptions" => [],
            "foreignModel" => "",
            "foreignKey" => "",
            "localKey" => "",
            "relationType" => "",
            "nullable" => false,
        ];
    }

    public function addRelationshipFieldRow(): void
    {
        $this->fields[] = $this->emptyField('relationship') + ['id' => uniqid(), 'foreignModelOptions' => Schema::pluck('modelName', 'id')];
    }

    public function removeFormFieldRow(int $index): void
    {
        unset($this->fields[$index]);
        $this->fields = array_values($this->fields);
    }

    public function setSchema(Schema $schema): void
    {
        $this->schema = $schema;

        $this->modelName = $schema->modelName;
    }

    public function store(): void
    {
        $this->validate();

        Schema::create($this->only(['modelName']));
    }

    public function update(): void
    {
        $this->validate();

        $this->schema->update(
            $this->all()
        );

        $this->reset();
    }
}
