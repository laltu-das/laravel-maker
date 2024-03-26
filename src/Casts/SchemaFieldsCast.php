<?php

namespace Laltu\LaravelMaker\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SchemaFieldsCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        // Decode the JSON from the database into a PHP array.
        // You can manipulate the $value array here if needed,
        // for example, to ensure certain keys are always set.

        return json_decode($value, true);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string|false
    {
        // Validate or manipulate $value before saving if needed.
        // This is where you could ensure your JSON structure is adhered to.

        // Encode the PHP array into a JSON string for storage.
        return json_encode($value);
    }
}
