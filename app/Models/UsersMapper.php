<?php

namespace Models;

use Library\MVC\Model\BaseMapper;

class UsersMapper extends BaseMapper
{
    protected static $objectClass = 'Models\User';
    protected static $tableName = 'users';

    /**
     * @param $email
     * @return mixed|null
     */
    public function findByEmail($email)
    {
        $result = $this->adapter->select(
            'SELECT * FROM ' . static::$tableName . ' WHERE email="' . $email . '" AND is_deleted = 0 LIMIT 1'
        );
        if (0 === count($result)) {
            return null;
        }
        $row = current($result);

        return $this->mapObject($row);
    }
}