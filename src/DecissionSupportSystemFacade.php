<?php

namespace Fanboykun\DecissionSupportSystem;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Fanboykun\DecissionSupportSystem\Skeleton\SkeletonClass
 */
class DecissionSupportSystemFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'decissionsupportsystem';
    }
}
