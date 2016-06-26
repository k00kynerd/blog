<?php

namespace Library\MVC\Controller;

use Library\DependencyInjection\DIRegistry;
use Library\MVC\Application;
use Library\Request;

abstract class BaseController
{
    /** @var Application */
    protected $app;
    /** @var Request */
    protected $request;

    /**
     * BaseController constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->request = DIRegistry::getDI()->get('request');
    }

}