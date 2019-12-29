<?php


namespace Jiejunf\Resourceful;


class BaseAdapter
{

    /** @var mixed */
    protected $adapter;

    public static function convert($adapter)
    {
        $container = new static();
        if (isset($adapter)) {
            $container->adapter = $adapter;
        }
        return $container;
    }

    public function __set($name, $value)
    {
        return $this->adapter->$name = $value;
    }

    public function __call($name, $arguments)
    {
        return $this->adapter->$name(...$arguments);
    }

    public function __get($name)
    {
        return $this->adapter->$name;
    }
}
