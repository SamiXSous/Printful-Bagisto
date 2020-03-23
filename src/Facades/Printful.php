<?php

namespace SamiXSous\Printful\Facades;

use Illuminate\Support\Facades\Facade;

class Printful extends Facade{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'printful';
    }
}