<?php

namespace Library\DependencyInjection;

abstract class AbstractDI
{
    /** @var array */
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