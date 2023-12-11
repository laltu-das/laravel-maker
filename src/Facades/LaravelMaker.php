<?php

namespace Laltu\LaravelMaker\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laltu\LaravelMaker\Skeleton\SkeletonClass
 */
class LaravelMaker extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     * @method static string css()
     * @method static string js()
     */
    protected static function getFacadeAccessor(): string
    {
        return \Laltu\LaravelMaker\LaravelMaker::class;
    }
}
