<?php

namespace Controllers;

use Library\MVC\Controller\BaseController;

class Errors extends BaseController
{
    public function NotFoundException($message)
    {
        return '404 ' . $message;
    }

    public function UnauthorizedException($message)
    {
        return '403 ' . $message;
    }

    public function BadRequestException($message)
    {
        return '400 ' . $message;
    }
}