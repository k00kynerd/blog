<?php
use Library\MVC\Router;

$router = new Router();

//Authorization
$router->map('/login', 'POST', '\Controllers\Auth::login');
$router->map('/logout', 'POST', '\Controllers\Auth::logout');

//Blog posts
$router->map('/posts', 'POST', '\Controllers\Posts::create');
$router->map('/posts', 'GET', '\Controllers\Posts::getList');
$router->map('/posts/([0-9]+)', 'GET', '\Controllers\Posts::getObject');

//Posts comments
$router->map('/posts/([0-9]+)/comments', 'POST', '\Controllers\Comments::create');
$router->map('/posts/([0-9]+)/comments', 'GET', '\Controllers\Comments::getList');
$router->map('/posts/([0-9]+)/comments/([0-9]+)', 'GET', '\Controllers\Comments::getObject');

//ErrorHandler
$router->setErrorHandlerController('\Controllers\Errors');

$config = [
    'router' => $router
];

return $config;