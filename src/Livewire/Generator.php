<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class Generator extends Component
{

    public $table_name;
    public array $formRelationalFields = [];

    public array $formFields = [
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

    public function show($name): void
    {
        $schema = $this->getFileContentByName($name);

        $this->table_name = $schema['name'];
        $this->formFields = $schema['formFields'];
        $this->formRelationalFields = $schema['formRelationalFields'];
    }

    private function getFileContentByName($fileName): Collection
    {
        $filePath = storage_path("laravel-maker/schema/{$fileName}.json");

        // Check if the file exists
        if (!File::exists($filePath)) {
            // Return an empty collection if the file does not exist
            return collect();
        }

        // Read the JSON content from the file
        $jsonContent = File::get($filePath);

        // Decode JSON content as an associative array
        $decodedData = json_decode($jsonContent);

        // Check if the decoding was successful
        if (json_last_error() === JSON_ERROR_NONE) {
            // Return a collection containing the decoded data
            return collect($decodedData);
        }

        // If decoding fails, return an empty collection
        return collect();
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



    private function getFileContent(): Collection
    {
        $directoryPath = storage_path("laravel-maker/schema");
        $allFileContents = collect(); // Initialize an empty collection

        // Check if the directory exists
        if (!File::isDirectory($directoryPath)) {
            // Return an empty collection if the directory does not exist
            return $allFileContents;
        }

        // Get all files in the directory
        $files = File::files($directoryPath);

        foreach ($files as $file) {
            // Read the JSON content from each file
            $jsonContent = File::get($file);

            // Decode JSON content
            $data = json_decode($jsonContent);

            // Add the data to the collection
            $allFileContents->push($data);
        }

        // Return a collection of objects
        return $allFileContents;
    }

    public function render()
    {
        $schemas = $this->getFileContent();

        return view('laravel-maker::livewire.generator', compact('schemas'))->layout(AppLayout::class);
    }
}