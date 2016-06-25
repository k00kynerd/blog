<?php

namespace Library\MVC;

use Library\MVC\Exceptions\ApplicationException;
use Library\MVC\Exceptions\NotFoundException;
use Library\Request;

class Router
{
    /** @var array */
    private $routes = [];

    /** @var string  */
    private $basePath = '';

    /** @var array */
    public static $allowMethods = [
        'GET',
        'POST',
        'PUT',
        'DELETE'
    ];

    /**
     * @param string $basePath
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = (string)$basePath;

        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * @param string $mask
     * @param string $method
     * @param string $action
     * @throws ApplicationException
     * @return $this
     */
    public function map($mask, $method, $action)
    {
        if (!in_array($method, self::$allowMethods, true)) {
            throw new ApplicationException('Not allow http method in route ' . $method);
        }
        list($controller, $action) = explode('::', $action);

        if (!$this->checkControllerActionExist($controller, $action)) {
            throw new ApplicationException('Not exist ' . $controller . '::' . $action . ' in routes');
        }

        $this->routes[$method][] = [
            'mask' => addcslashes($this->getBasePath() . $mask, '/'),
            'controller' => $controller,
            'action' => $action,
        ];

        return $this;
    }

    /**
     * @param Request $request
     * @return array
     * @throws NotFoundException
     */
    public function handle(Request $request)
    {
        $actionParams = [];
        $route = [];
        if (array_key_exists($request->getMethod(), $this->routes)) {
            foreach ($this->routes[$request->getMethod()] as $route) {
                preg_match('/^' . $route['mask'] . '$/i', $request->getUri(), $actionParams);
                if (!empty($actionParams)) {
                    break;
                }
            }
        }
        if (empty($actionParams)) {
            throw new NotFoundException('Method not exist');
        }
        array_shift($actionParams);

        return [
            $route['controller'],
            $route['action'],
            $actionParams
        ];
    }

    /**
     * @param $controller
     * @param $action
     * @return bool
     */
    protected function checkControllerActionExist($controller, $action)
    {
        if (
            empty($controller) ||
            empty($action) ||
            !class_exists($controller, true) ||
            !method_exists($controller, $action)
        ) {
            return false;
        }

        return true;
    }
}