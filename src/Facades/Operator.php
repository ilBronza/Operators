<?php

namespace ilBronza\Operator\Facades;

use Illuminate\Support\Facades\Facade;

class Operator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'operator';
    }
}
