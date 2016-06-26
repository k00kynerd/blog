<?php
use Library\MVC\Router;
use Library\Database\DbConf;
use Library\MVC\ACL;

$router = new Router();

//Authorization
$router->map('/login', 'POST', '\Controllers\AuthController::login');
$router->map('/logout', 'POST', '\Controllers\AuthController::logout');

//Blog posts
$router->map('/posts', 'POST', '\Controllers\PostsController::create');
$router->map('/posts', 'GET', '\Controllers\PostsController::getList');
$router->map('/posts/([0-9]+)', 'GET', '\Controllers\PostsController::getObject');

//Posts comments
$router->map('/posts/([0-9]+)/comments', 'POST', '\Controllers\CommentsController::create');
$router->map('/posts/([0-9]+)/comments', 'GET', '\Controllers\CommentsController::getList');
$router->map('/posts/([0-9]+)/comments/([0-9]+)', 'GET', '\Controllers\CommentsController::getObject');

//ErrorHandler
$router->setErrorHandlerController('\Controllers\ErrorsController');

$dbConfig = new DbConf([
    'type' => 'mysql',
    'host' => '127.0.0.1',
    'user' => 'root',
    'password' => 'T2bLsyw61',
    'name' => 'blog'
]);

$acl = new ACL();
$acl->addRule('\Controllers\AuthController', ['login'], true);
$acl->addRule('\Controllers\AuthController', ['logout'], false);
$acl->addRule('\Controllers\PostsController', ['getList', 'getObject'], true);
$acl->addRule('\Controllers\PostsController', ['create'], false);
$acl->addRule('\Controllers\CommentsController', ['create', 'getList', 'getObject'], true);

$config = [
    'router' => $router,
    'dbConf' => $dbConfig,
    'acl' => $acl
];

return $config;