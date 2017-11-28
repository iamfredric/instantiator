<?php

namespace Iamfredric\Instantiator;

class InstantiationsBinder
{
    /**
     * @var array
     */
    protected static $bindings = [];

    /**
     * @param $key
     * @param $value
     */
    public static function bind($key, $value)
    {
        static::$bindings[$key] = $value;
    }

    /**
     * @param $key
     *
     * @return bool
     */
    public static function has($key)
    {
        return isset(static::$bindings[$key]);
    }

    /**
     * @param $key
     * @param null $classname
     *
     * @return object
     */
    public static function resolve($key, $classname = null)
    {
        $instance = static::$bindings[$key];

        if (is_callable($instance)) {
            return $instance($classname);
        }

        return (new Instantiator($classname ?: $instance))->call();
    }
}