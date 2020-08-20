<?php

namespace app\controller\common;

class Registry
{
    /**
     * @var array $objs
     */
    private static $objs = [];

    /**
     * @param string $alias
     * @return object|boolean
     */
    public static function get(string $alias)
    {
        if (!isset(self::$objs[$alias])) {
            return false;
        }
        return self::$objs[$alias];
    }

    /**
     * @param string $alias
     * @param Object $obj
     */
    public static function set(string $alias, Object $obj): void
    {
        if (!isset(self::$objs[$alias])) {
            self::$objs[$alias] = $obj;
        }
    }

    /**
     * @param string $alias
     */
    public static function unset(string $alias): void
    {
        if (isset(self::$objs[$alias])) {
            unset(self::$objs[$alias]);
        }
    }
}