<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laltu\LaravelMaker\Casts\SchemaFieldsCast;

class Schema extends Model
{
    use SoftDeletes;

    protected $connection = 'sqlite';

    protected $fillable = [
        'model_name',
        'fields',
    ];

    protected function casts(): array
    {
        return [
            'fields' => SchemaFieldsCast::class
        ];
    }
}
