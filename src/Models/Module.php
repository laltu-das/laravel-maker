<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $connection = 'sqlite';

    protected $fillable = [
        'moduleName',
        'controllerType',
        'controllerName',
    ];
}
