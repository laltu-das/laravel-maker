<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Str;
use Laltu\LaravelMaker\Models\Schema;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class SchemaImportFromSql extends Component
{
    public $sqlData;

    protected $rules = [
        'sqlData' => 'required|string',
    ];

    public function save()
    {
        $this->validate();


        $schemaData = $this->parseSql($this->sqlData);

        dd($this->sqlData,$schemaData);

        $this->schema->fill([
            'modelName' => $this->modelName,
            'fields' => $this->fields,
        ])->save();

        session()->flash('message', 'Schema ' . ($this->mode === 'edit' ? 'updated' : 'created') . ' successfully.');

        return redirect()->route('schema');
    }

    private function parseSql(string $sqlData): array
    {
        // Remove spaces and extra semicolon before parsing
        $sql = trim(preg_replace('/;s+/', ';', $sqlData));

        // Initialize output structure
        $schemaData = [
            'table' => '',
            'columns' => [],
            'indexes' => [],
            'foreignKeys' => []
        ];

        // Regular Expressions for Parsing
        $patterns = [
            'table' => '/CREATE TABLE `(?P<table>[^`]+)`/',
            'column' => '/`(?P<name>[^`]+)` (?P<type>[^\s]+)(.*?)(?=\s*\(|$)/',
            'index' => '/KEY `(?P<name>[^`]+)` \(`(?P<columns>[^`]+)`\)/',
            'foreignKey' => '/CONSTRAINT `(?P<name>[^`]+)` FOREIGN KEY \(`(?P<column>[^`]+)`\) REFERENCES `(?P<references>[^`]+)` \(`(?P<referencesColumn>[^`]+)`\)( ON DELETE (?P<onDelete>[^\s]+))?( ON UPDATE (?P<onUpdate>[^\s]+))?/',
        ];

        // Match Table Name
        preg_match($patterns['table'], $sql, $tableMatches);
        $schemaData['table'] = $tableMatches['table'];

        // Match Columns
        preg_match_all($patterns['column'], $sql, $columnMatches, PREG_SET_ORDER);
        foreach ($columnMatches as $match) {
            $column = [
                'name' => $match['name'],
                'type' => $match['type'],
                'attributes' => $this->parseColumnAttributes($match[0])
            ];
            $schemaData['columns'][] = $column;
        }

        // Match Indexes
        preg_match_all($patterns['index'], $sql, $indexMatches, PREG_SET_ORDER);
        foreach ($indexMatches as $match) {
            $schemaData['indexes'][] = [
                'name' => $match['name'],
                'columns' => explode(',', $match['columns'])
            ];
        }

        // Match Foreign Keys
        preg_match_all($patterns['foreignKey'], $sql, $fkMatches, PREG_SET_ORDER);
        foreach ($fkMatches as $match) {
            $schemaData['foreignKeys'][] = [
                'name' => $match['name'],
                'column' => $match['column'],
                'references' => $match['references'],
                'referencesColumn' => $match['referencesColumn'],
                'onDelete' => $match['onDelete'] ?? null,
                'onUpdate' => $match['onUpdate'] ?? null
            ];
        }

        return $schemaData;
    }

    // Helper Function to Parse Column Attributes
    private function parseColumnAttributes(string $columnDefinition): array
    {
        $attributes = [];
        $attributePattern = '/(\w+)\s*(?:\(([^)]+)\))?\s*(.*)/'; // Match attribute name, length, other options
        preg_match_all($attributePattern, $columnDefinition, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            $attributes[$match[1]] = isset($match[2]) ? $match[2] : null;
        }
        return $attributes;
    }

    public function render()
    {
        return view('laravel-maker::livewire.schema-import-sql')->layout(AppLayout::class);
    }
}
