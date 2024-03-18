<?php

namespace Laltu\LaravelMaker;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laltu\LaravelMaker\Skeleton\SkeletonClass
 */
class LaravelMakerFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-maker';
    }
}
