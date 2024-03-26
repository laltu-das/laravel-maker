<?php

namespace Laltu\LaravelMaker\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class SchemaFieldsCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {

    }
}
