<?php

namespace Controllers;

use Library\DependencyInjection\DIRegistry;
use Library\MVC\Controller\BaseController;
use Library\MVC\Exceptions\BadRequestException;
use Library\MVC\Exceptions\UnauthorizedException;
use Library\Session;
use Models\User;
use Models\UsersMapper;


class AuthController extends BaseController
{

    /**
     * @return array
     * @throws BadRequestException
     * @throws UnauthorizedException
     */
    public function login()
    {
        /** @var array $requestData */
        $requestData = $this->request->getBodyJson();
        if (
            !array_key_exists('email', $requestData) ||
            !array_key_exists('password', $requestData) ||
            filter_var($requestData['email'], FILTER_VALIDATE_EMAIL) === false
        ) {
            throw new BadRequestException('Wrong request data');
        }
        $mapper = new UsersMapper();
        /** @var User $user */
        $user = $mapper->findByEmail($requestData['email']);

        if($user === null || !password_verify($requestData['password'], $user->getPasswordHash() )) {
            throw new UnauthorizedException('Wrong auth data');
        }
        /** @var Session $session */
        $session = DIRegistry::getDI()->get('session');
        $session->set('userId', $user->getId());

        return json_encode([
            'auth' => $session->getId()
        ]);
    }

    /**
     * @return array
     */
    public function logout()
    {
        /** @var Session $session */
        $session = DIRegistry::getDI()->get('session');
        $session->destroy();

        return null;
    }
}