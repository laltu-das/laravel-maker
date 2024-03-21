<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schema extends Model
{
    use SoftDeletes;

    protected $connection = 'sqlite';

    protected $fillable = [
        'modelName',
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(SchemaField::class);
    }
}
