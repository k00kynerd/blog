<?php

namespace Library\MVC;

USE Library\DependencyInjection\DIRegistry;
use Library\MVC\Exceptions\ApplicationException;
use Library\MVC\Exceptions\BadRequestException;
use Library\MVC\Exceptions\NotFoundException;
use Library\MVC\Exceptions\UnauthorizedException;
use Library\Request;

class Application
{
    /** @var string */
    public $controller;

    /** @var string */
    public $action;

    public function run()
    {
        /** @var Router $router */
        $router = DIRegistry::getDI()->get('router');
        /** @var Request $request */
        $request = DIRegistry::getDI()->get('request');

        if ($router === null || !($router instanceof Router)) {
            throw new ApplicationException('Router not initialize');
        }
        try {
            list($this->controller, $this->action, $params) = $router->handle($request);

            //TODO: Hear we have ACL check
            
            $controller = new $this->controller($this);
            echo call_user_func_array([$controller, $this->action], $params);

        } catch (NotFoundException $e) {
            $router->errorHandler($e);
        } catch (UnauthorizedException $e) {
            $router->errorHandler($e);
        } catch (BadRequestException $e) {
            $router->errorHandler($e);
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