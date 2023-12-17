<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\File;
use Laltu\LaravelMaker\View\Components\AppLayout;
use Livewire\Component;

class ListModule extends Component
{
    public $formRelationalFields = [];

    public $formFields = [];

    public function addFormFieldRow(): void
    {
        $this->formFields[] = ['name' => '', 'email' => ''];
    }

    public function removeFormFieldRow($index): void
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields); // Re-index the array
    }

    public function addFormRelationalFieldRow(): void
    {
        $this->formRelationalFields[] = ['name' => '', 'email' => ''];
    }

    public function removeFormRelationalFieldRow($index): void
    {
        unset($this->formRelationalFields[$index]);
        $this->formRelationalFields = array_values($this->formRelationalFields); // Re-index the array
    }

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


    public function render()
    {
        $allFileContents = $this->getFileContent();

        dump($allFileContents);
        return view('laravel-maker::livewire.list-module')->layout(AppLayout::class);
    }
}