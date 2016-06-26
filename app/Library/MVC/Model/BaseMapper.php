<?php

namespace Library\MVC\Model;

use Library\Database\Connections\Mysql;
use Library\DependencyInjection\DIRegistry;
use Library\MVC\Exceptions\ApplicationException;
use Library\Utils\ArrayUtil;

abstract class BaseMapper
{
    const MAX_LIMIT = 100;

    /** @var Mysql */
    protected $adapter;

    /** @var string */
    protected static $tableName;

    /** @var int  */
    protected $limit = self::MAX_LIMIT;

    /** @var int  */
    protected $offset = 0;

    /** @var string */
    protected static $objectClass;

    public function __construct()
    {
        $this->adapter = DIRegistry::getDI()->get('db');
    }

    /**
     * @param $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $limit = abs((int)$limit);
        $this->limit = ($limit > self::MAX_LIMIT || $limit === 0) ? self::MAX_LIMIT : abs($limit);

        return $this;
    }

    /**
     * @param $offset
     * @return $this
     */
    public function setOffset($offset)
    {
        $this->offset = $offset = abs((int)$offset);

        return $this;
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function findById($id)
    {
        $result = $this->adapter->select(
            'SELECT * FROM ' . static::$tableName . ' WHERE id=' . (int)$id . ' AND is_deleted = 0 LIMIT 1'
        );
        if (0 === count($result)) {
            return null;
        }
        $row = current($result);

        return $this->mapObject($row);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $resultSet = $this->adapter->select(
            'SELECT * FROM ' . static::$tableName . ' WHERE is_deleted = 0 LIMIT ' . $this->limit . ' OFFSET ' . $this->offset
        );
        $entries = [];

        foreach ($resultSet as $row) {
            $entries[] = $this->mapObject($row);
        }

        return $entries;
    }

    /**
     * @param array $row
     * @return mixed
     */
    protected function mapObject(array $row)
    {
        $object = new static::$objectClass();
        ArrayUtil::mapObjectFromArray($object, $row);

        return $object;
    }
}