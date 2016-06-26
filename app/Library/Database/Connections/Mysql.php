<?php
namespace Library\Database\Connections;

use Library\Database\ConnectionInterface;
use Library\Database\DbConf;
use Library\MVC\Exceptions\DatabaseException;

class Mysql implements ConnectionInterface
{
    /** @var \mysqli */
    protected static $connection;
    /** @var DbConf */
    protected static $conf;

    /**
     * Mysql constructor.
     * @param DbConf $conf
     */
    public function __construct(DbConf $conf)
    {
        static::$conf = $conf;
    }

    /**
     * @return \mysqli
     * @throws DatabaseException
     */
    public function connect()
    {
        if (!isset(self::$connection)) {
            self::$connection = new \mysqli(
                static::$conf->getHost(),
                static::$conf->getUser(),
                static::$conf->getPassword(),
                static::$conf->getName()
            );
        }
        if (
            self::$connection === false ||
            self::$connection->connect_error !== null
        ) {
            throw new DatabaseException('Can\'t connect to Database');
        }
        return self::$connection;
    }

    /**
     * @param $query
     * @return bool|\mysqli_result
     * @throws DatabaseException
     */
    public function query($query)
    {
        return $this->connect()->query($query);
    }

    /**
     * @param $query
     * @return array|bool
     */
    public function select($query)
    {
        $rows = [];
        $result = $this->query($query);
        if ($result === false) {
            return false;
        }
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        return $rows;
    }

    /**
     * @return int
     * @throws DatabaseException
     */
    public function getLastId()
    {
        return $this->connect()->insert_id;
    }

    /**
     * @return string
     * @throws DatabaseException
     */
    public function error()
    {
        return $this->connect()->error;
    }

    /**
     * @param $value
     * @return string
     * @throws DatabaseException
     */
    public function quote($value)
    {
        $connection = $this->connect();
        return "'" . $connection->real_escape_string($value) . "'";
    }
}