<?php

namespace Library\Database;

use Library\MVC\Exceptions\DatabaseException;

class ConnectionFactory
{

    public static function factory(DbConf $config)
    {
        $className = __NAMESPACE__ . '\Connections\\' . ucfirst($config->getType());

        if (!class_exists($className)) {
            throw new DatabaseException('Not supported DB type');
        }

        return new $className($config);
    }
}