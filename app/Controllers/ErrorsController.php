<?php

namespace Controllers;

use Library\MVC\Controller\BaseController;

class ErrorsController extends BaseController
{
    public function NotFoundException($message)
    {
        http_response_code(404);
        return json_encode([
           'error' => $message
        ]);
    }

    public function UnauthorizedException($message)
    {
        http_response_code(403);
        return json_encode([
            'error' => $message
        ]);
    }

    public function BadRequestException($message)
    {
        http_response_code(400);
        return json_encode([
            'error' => $message
        ]);
    }
}