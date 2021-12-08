<?php

NameSpace Tests;

class TestUtils
{
    /**
     * Flat input array
     *
     * @param Array $array
     * @return Array
     */
    public static function array_flatten($array) {
        if (!is_array($array)) {
          return false;
        }
        $result = array();
        foreach ($array as $key => $value) {
          if (is_array($value)) {
            $result = array_merge($result, self::array_flatten($value));
          } else {
            $result = array_merge($result, array($key => $value));
          }
        }
        return $result;
    }
}
