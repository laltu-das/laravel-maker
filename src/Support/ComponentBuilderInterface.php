<?php

namespace Laltu\LaravelMaker\Support;

use Exception;

interface ComponentBuilderInterface
{
    /**
     * Sets up a script for a specific model and route with the given fields.
     *
     * @param string $model The model identifier.
     * @param string $route The route to associate the script with.
     * @param array $fields The fields to be included in the script setup.
     * @throws Exception if the model or route is invalid.
     */
    public function addScriptSetup(string $model, string $route, array $fields);

    /**
     * Adds a field to the form.
     *
     * @param array $field The field configuration.
     *     Supported keys:
     *     - type: The type of the field. Allowed values are 'text', 'password', 'email'.
     *     - name: The name of the field.
     *     - label: The label for the field (optional).
     *     - value: The default value for the field (optional).
     *     - attributes: Additional HTML attributes for the field (optional).
     *
     * @throws Exception If the field type is not supported.
     */
    public function addField(array $field);

    /**
     * Build method.
     *
     * Builds and returns a string representation of something.
     *
     * @return string The built string.
     */
    public function build(): string;

    /**
     * Create a field based on the provided field type.
     *
     * @param array $field The field configuration.
     *                     The field array should contain at least a 'type' key that represents the field type.
     *                     The other keys in the field array will depend on the specific field type being created.
     * @throws Exception If the provided field type is not supported.
     */
    public function createField(array $field);
}
