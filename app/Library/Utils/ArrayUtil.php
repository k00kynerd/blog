<?php

namespace Library\Utils;

class ArrayUtil
{
    /**
     * @param $object
     * @param array $array
     */
    public static function mapObjectFromArray($object, array $array)
    {
        foreach ($array as $key => $value) {
            $setterName = 'set' . ucfirst(str_replace('_', '', $key));
            if (method_exists($object, $setterName)) {
                $object->$setterName($value);
            }
        }
    }
}