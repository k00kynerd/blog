<?php

namespace Controllers;

use Library\MVC\Controller\BaseController;

class Auth extends BaseController
{

    public function login()
    {
        return 'login';
    }

    public function logout()
    {
        return 'logout';
    }
}