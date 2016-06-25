<?php
use Library\DependencyInjection\DI;
use Library\Session;
use Library\Request;

$di = new DI($config);
$di->set('session', new Session());
$di->set('request', new Request());

$di->set('database', function () {
    return 'database';
});

return $di;