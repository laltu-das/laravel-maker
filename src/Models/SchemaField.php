<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchemaField extends Model
{
    use SoftDeletes;
    protected $connection = 'sqlite';

    protected $fillable = [
        'fieldName',
        'dataType',
        'validation',
        'searchable',
        'fillable',
        'nullable',
        'relationType',
        'foreignModel',
        'foreignKey',
        'localKey',
    ];

    public function schema(): BelongsTo
    {
        return $this->belongsTo(Schema::class);
    }
}
