<?php

namespace Library\MVC;

use Library\DependencyInjection\DI;
use Library\MVC\Exceptions\ApplicationException;
use Library\MVC\Exceptions\NotFoundException;
use Library\MVC\Exceptions\UnauthorizedException;
use Library\Request;

class Application
{
    /** @var DI */
    protected $di;

    /** @var string */
    public $controller;

    /** @var string */
    public $action;

    public function __construct(DI $di)
    {
        $this->di = $di;
    }

    /**
     * @return DI
     */
    public function getDI()
    {
        return $this->di;
    }

    public function handle()
    {
        /** @var Router $router */
        $router = $this->getDI()->get('router');
        /** @var Request $request */
        $request = $this->getDI()->get('request');

        if ($router === null || !($router instanceof Router)) {
            throw new ApplicationException('Router not initialize');
        }
        try {
            list($this->controller, $this->action, $params) = $router->handle($request);

            //TODO: Hear we have ACL check
            $controller = new $this->controller($this);
            echo call_user_func_array([$controller, $this->action], $params);

        } catch (NotFoundException $e) {
            //TODO: correctly handler
            //Here call Route error handler if exist
            echo $e->getMessage();
        } catch (UnauthorizedException $e) {
            //TODO: correctly handler
            //Here call Route error handler if exist
            echo $e->getMessage();
        }

        return $this;
    }

    public function getContent()
    {
        //Run This
        return '';
        //return print_r($this, 1);
    }
}