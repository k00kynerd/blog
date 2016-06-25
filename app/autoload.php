<?php

function __autoload($className) {
    $className = str_replace(['..', '\\'], ['', '/'], $className);
    require_once(__DIR__ . '/' . $className . '.php');
}