<?php
use Library\DependencyInjection\DI;
use Library\DependencyInjection\DIRegistry;
use Library\Session;
use Library\Request;
use Library\Database\ConnectionFactory;

$di = new DI($config);
$di->set('session', new Session());
$di->set('request', new Request());

if (array_key_exists('dbConf', $config)) {
    $di->set('db', ConnectionFactory::factory($config['dbConf']));
}

DIRegistry::setDI($di);

return $di;