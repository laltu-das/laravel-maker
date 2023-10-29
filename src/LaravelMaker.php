<?php

namespace Laltu\LaravelMaker;

class LaravelMaker
{
    /**
     * Get the default JavaScript variables for Telescope.
     *
     * @return array
     */
    public static function scriptVariables(): array
    {
        return [
            'path' => config('laravel-maker.path'),
        ];
    }

}
