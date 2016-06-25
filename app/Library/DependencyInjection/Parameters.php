<?php

namespace Library\DependencyInjection;


interface Parameters
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);
}