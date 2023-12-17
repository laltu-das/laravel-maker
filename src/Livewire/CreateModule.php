<?php

namespace Laltu\LaravelMaker\Livewire;

use Illuminate\Support\Facades\File;
use Livewire\Component;

class CreateModule extends Component
{
    public $formRelationalFields = [
        [
            "foreign_model" => "",
            "foreign_key" => "",
            "local_key" => "",
            "relation_type" => "",
        ]
    ];

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

    public function addFormFieldRow()
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

    public function removeFormFieldRow($index)
    {
        unset($this->formFields[$index]);
        $this->formFields = array_values($this->formFields); // Re-index the array
    }

    public function addFormRelationalFieldRow()
    {
        $this->formRelationalFields[] = [
            "foreign_model" => "",
            "foreign_key" => "",
            "local_key" => "",
            "relation_type" => "",
        ];
    }

    public function removeFormRelationalFieldRow($index)
    {
        unset($this->formRelationalFields[$index]);
        $this->formRelationalFields = array_values($this->formRelationalFields); // Re-index the array
    }

    public function submit()
    {

//        dd($this->formRelationalFields,$this->formFields);

        $this->generateContent('module',         array_merge($this->formRelationalFields,$this->formFields));

    }


    private function generateContent($name, $fields)
    {

        $filePath = storage_path("laravel-maker/{$name}.json");

        $formattedFields = array_map(function ($field) {
            return [['field' => $field]];
        }, $fields);

        $data = [
            'name' => $name,
            'fields' => $formattedFields,
        ];

        // Convert array to JSON
        $jsonContent = json_encode($data, JSON_PRETTY_PRINT);

        // Ensure the directory exists
        File::ensureDirectoryExists(dirname($filePath));

        // Write the JSON content to the file
        File::put($filePath, $jsonContent);
        $this->js("alert('Post saved!')");

//        $this->info("Resource file '{$name}.json' created successfully.");
    }

    public function render()
    {
        return view('laravel-maker::livewire.create-module')->extends('laravel-maker::components.layouts.app');
    }
}