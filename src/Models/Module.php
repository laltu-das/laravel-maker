<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $connection = 'sqlite';

    protected $fillable = [
        'module_name',
        'controller_type',
        'controller_name',
        'form_fields',
        'api_fields',
    ];


    protected function casts(): array
    {
        return [
            'form_fields' => 'json',
            'api_fields' => 'json',
        ];
    }
}
