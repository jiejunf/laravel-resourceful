<?php


namespace Jiejunf\Resourceful;


/**
 * Class Resourceful
 * @package Resourceful
 * @method static string currentResourceName()
 * @method static string getActionMethod()
 *
 * @uses \Jiejunf\Resourceful\ResourceHelper::currentResourceName()
 * @uses \Jiejunf\Resourceful\ResourceHelper::getActionMethod()
 * @see  ResourceHelper
 */
class Resourceful
{
    public static function __callStatic($name, $arguments)
    {
        return self::helper()->$name();
    }

    /**
     * @return mixed
     */
    private static function helper(): ResourceHelper
    {
        return resolve(ResourceHelper::class);
    }
}
