<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\File;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ModuleList extends Component
{
    public $module_name;

    private function getFileContent(): array
    {
        $directoryPath = storage_path("laravel-maker");
        $allFileContents = [];

        // Check if the directory exists
        if (!File::isDirectory($directoryPath)) {
            // Return an empty array if the directory does not exist
            return $allFileContents;
        }

        // Get all files in the directory
        $files = File::files($directoryPath);

        foreach ($files as $file) {
            // Read the JSON content from each file
            $jsonContent = File::get($file);

            // Decode JSON content
            $data = json_decode($jsonContent, true);

            // Add the data to the array
            $allFileContents[] = $data;
        }

        return $allFileContents;
    }


    public function submit(): void
    {

        $filePath = storage_path("laravel-maker/modules/{$this->module_name}.json");

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
                'name' => $this->module_name,
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
        $modules = $this->getFileContent();

        return view('laravel-maker::livewire.module', compact('modules'))->layout(AppLayout::class);
    }
}