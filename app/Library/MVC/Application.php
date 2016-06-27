<?php

namespace Library\MVC;

use Library\DependencyInjection\DIRegistry;
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
        /** @var ACL $acl */
        $acl = DIRegistry::getDI()->get('acl');

        if ($router === null || !($router instanceof Router)) {
            throw new ApplicationException('Router not initialize');
        }
        try {
            list($this->controller, $this->action, $params) = $router->handle($request);
            if (!$acl->isAllowed($this->controller, $this->action)) {
                throw new UnauthorizedException('Method is not allowed');
            }
            $controller = new $this->controller($this);
            echo call_user_func_array([$controller, $this->action], $params);

        } catch (NotFoundException $e) {
            echo $router->errorHandler($e);
        } catch (UnauthorizedException $e) {
            echo $router->errorHandler($e);
        } catch (BadRequestException $e) {
            echo $router->errorHandler($e);
        }

        return $this;
    }
}