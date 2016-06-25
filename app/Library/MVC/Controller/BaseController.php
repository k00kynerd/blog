<?php

namespace Library\MVC\Controller;

use Library\MVC\Application;

abstract class BaseController
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

}