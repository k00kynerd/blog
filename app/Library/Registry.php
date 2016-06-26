<?php
namespace Library;

abstract class Registry
{
    const DI_KEY = 'di';

    /** @var array */
    protected static $storedValues = [];

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$storedValues[$key] = $value;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function get($key)
    {
        return self::$storedValues[$key];
    }
}