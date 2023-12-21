<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schema extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'modelName',
        'fields',
        'relationalFields',
    ];

    protected $casts = ['fields' => 'json','relationalFields' => 'json'];
}
