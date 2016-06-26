<?php
namespace Library\MVC;

use Library\DependencyInjection\DIRegistry;
use Library\Request;
use Library\Session;

class ACL
{
    const X_TOKEN_HEADER = 'X-Token';

    /** @var array */
    protected $rules = [];

    /**
     * @param string $controller
     * @param array $actions
     * @param bool $forGuest
     */
    public function addRule($controller, array $actions, $forGuest)
    {
        foreach ($actions as $action) {
            $this->rules[$controller][$action] = [
                'guest' => (bool)$forGuest,
            ];
        }
    }

    /**
     * @param string $controller
     * @param string $action
     * @return bool
     */
    public function isAllowed($controller, $action)
    {
        /** @var Session $session */
        $session = DIRegistry::getDI()->get('session');
        /** @var Request $request */
        $request = DIRegistry::getDI()->get('request');

        if (
            array_key_exists($controller, $this->rules) &&
            array_key_exists($action, $this->rules[$controller])
        ) {
            $rule = $this->rules[$controller][$action];
            if ($rule['guest'] === true) {
                return true;
            }
            if (
                $session->get('auth', false) === true &&
                $request->getHeader(self::X_TOKEN_HEADER) === $session->getId()
            ) {
                return true;
            }
        }

        return false;
    }

}