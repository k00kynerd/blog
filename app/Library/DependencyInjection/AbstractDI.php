<?php

namespace Library\DependencyInjection;


abstract class AbstractDI
{
    protected $storage;

    /**
     * AbstractDI constructor.
     * @param $storage
     */
    public function __construct($storage)
    {
        $this->storage = $storage;
    }
}