<?php

namespace Mimisk\Pinakas\Facades;

use Illuminate\Support\Facades\Facade;

class Pinakas extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pinakas';
    }
}
