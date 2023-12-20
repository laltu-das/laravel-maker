<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\File;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class CreateSchema extends Component
{
    public $table_name;
    public $formRelationalFields = [];

    public $formFields = [
        [
            "field_name" => false,
            "validation" => "Quis aut nisi error ",
            "primary" => true,
            "is_foreign" => true,
            "searchable" => false,
            "fillable" => false,
            "in_form" => true,
            "in_index" => true,
            "db_type" => "longText",
            "html_type" => "text"
        ]
    ];

    public function addFormFieldRow(): void
    {
        $this->formFields[] = [
            "field_name" => false,
            "validation" => "Quis aut nisi error ",
            "primary" => true,
            "is_foreign" => true,
            "searchable" => false,
            "fillable" => false,
            "in_form" => true,
            "in_index" => true,
            "db_type" => "longText",
            "html_type" => "text"
        ];
    }

    public function removeFormFieldRow($index): void
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields); // Re-index the array
    }

    public function addFormRelationalFieldRow(): void
    {
        $this->formRelationalFields[] = [
            "foreign_model" => "",
            "foreign_key" => "",
            "local_key" => "",
            "relation_type" => "",
        ];
    }

    public function removeFormRelationalFieldRow($index): void
    {
        unset($this->formRelationalFields[$index]);
        $this->formRelationalFields = array_values($this->formRelationalFields); // Re-index the array
    }

    public function submit(): void
    {

        $filePath = storage_path("laravel-maker/schema/{$this->table_name}.json");

        // Check if the file already exists
        if (File::exists($filePath)) {
            // Read existing content
            $existingContent = File::get($filePath);

            // Decode existing JSON content
            $existingData = json_decode($existingContent, true);

            // Update the existing data with the new fields
            $existingData['formRelationalFields'] = $this->formRelationalFields;
            $existingData['formFields'] = $this->formFields;

            // Encode the updated data to JSON
            $jsonContent = json_encode($existingData, JSON_PRETTY_PRINT);
        } else {
            // If the file doesn't exist, create new data
            $data = [
                'name' => $this->table_name,
                'formRelationalFields' => $this->formRelationalFields,
                'formFields' => $this->formFields,
            ];

            // Encode the data to JSON
            $jsonContent = json_encode($data, JSON_PRETTY_PRINT);
        }

        // Ensure the directory exists
        File::ensureDirectoryExists(dirname($filePath));

        // Write the JSON content to the file
        File::put($filePath, $jsonContent);

        $this->js("alert('Schema {$this->table_name} saved!')");
    }

    public function render()
    {
        return view('laravel-maker::livewire.create-schema')->layout(AppLayout::class);
    }
}