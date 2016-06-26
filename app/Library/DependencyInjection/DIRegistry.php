<?php

namespace Library\DependencyInjection;

use Library\Registry;

class DIRegistry extends Registry
{
    /**
     * @param DI $di
     */
    public static function setDI(DI $di)
    {
        static::$storedValues[static::DI_KEY] = $di;
    }

    /**
     * @return DI
     */
    public static function getDI()
    {
        return static::$storedValues[static::DI_KEY];
    }
}