<?php

namespace DynamicLogger\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class DynamicLogger
 * @package DynamicLogger\Facades
 */
class DynamicLogger extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'dynamic.logger';
    }
}
