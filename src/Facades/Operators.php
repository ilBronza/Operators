<?php

namespace IlBronza\Operators\Facades;

use Illuminate\Support\Facades\Facade;

class Operators extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'operators';
    }
}
