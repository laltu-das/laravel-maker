<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class ListModule extends Component
{
    public $formRelationalFields = [];

    public $formFields = [];

    public function addFormFieldRow()
    {
        $this->formFields[] = ['name' => '', 'email' => ''];
    }

    public function removeFormFieldRow($index)
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields); // Re-index the array
    }

    public function addFormRelationalFieldRow()
    {
        $this->formRelationalFields[] = ['name' => '', 'email' => ''];
    }

    public function removeFormRelationalFieldRow($index)
    {
        unset($this->formRelationalFields[$index]);
        $this->formRelationalFields = array_values($this->formRelationalFields); // Re-index the array
    }

    private function getFileContent()
    {
        $directoryPath = storage_path("laravel-maker");
        $allFileContents = [];

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
dd($allFileContents);
        return $allFileContents;

    }


    public function render()
    {
        echo $directoryPath = storage_path("laravel-maker");
        $allFileContents = [];

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
        dump($allFileContents);
        return view('laravel-maker::livewire.list-module')->extends('laravel-maker::components.layouts.app');
    }
}