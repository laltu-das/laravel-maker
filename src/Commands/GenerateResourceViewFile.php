<?php

namespace Laltu\LaravelMaker\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateResourceViewFile extends Command
{
    protected $signature = 'resource-file:create {name : The name of the resource file} {--fields= : The fields for the resource file}';
    protected $description = 'Create a resource file with specified fields';

    public function handle(): void
    {
        $name = $this->argument('name');
        $fields = $this->option('fields');

        if (!$fields) {
            $this->error('Fields are required. Please use the --fields option.');
            return;
        }

        $fieldsArray = explode(',', $fields);

        $this->generateContent($name, $fieldsArray);
        $this->generateViewContent($name, $fieldsArray);
        $this->generateApiContent($name, $fieldsArray);
    }

    private function generateContent($name, $fields): void
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

        $this->info("Resource file '{$name}.json' created successfully.");
    }

    private function generateViewContent($name, $fields): void
    {

        $filePath = storage_path("laravel-maker/{$name}-view.json");

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

        $this->info("Resource file '{$name}.json' created successfully.");
    }

    private function generateApiContent($name, $fields): void
    {

        $filePath = storage_path("laravel-maker/{$name}-api.json");

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

        $this->info("Resource file '{$name}.json' created successfully.");
    }
}