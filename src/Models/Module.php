<?php

namespace Laltu\LaravelMaker\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;

    protected $fillable = ['moduleName', 'controllerType', 'controllerName','formFields','apiFields'];

    protected $casts = ['formFields'=>'json','apiFields'=>'json'];
}
