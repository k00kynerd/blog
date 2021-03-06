<?php
use Library\MVC\Application;

require_once  __DIR__ . '/app/autoload.php';

$config = include __DIR__ . '/app/config.php';
$di = include __DIR__ . '/app/services.php';

try {
    $application = new Application();
    $application->run();

} catch (\Exception $e) {
    echo $e->getMessage();
}