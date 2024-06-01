<?php

namespace Laltu\LaravelMaker;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use RuntimeException;

class LaravelMaker
{
    /**
     * Get the default JavaScript variables for Telescope.
     *
     * @return array
     */
    public static function scriptVariables()
    {
        return [
//            'path' => config('laravel-maker.path'),
//            'timezone' => config('app.timezone'),
//            'recording' => ! cache('laravel-maker:pause-recording'),
        ];
    }
}
