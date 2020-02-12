<?php


namespace Jiejunf\Resourceful;


/**
 * Class Resourceful
 * @package Resourceful
 * @method static string currentResourceName()
 * @method static string getActionMethod()
 * @method static mixed serviceConfig(string $key, $default = null, string $resource = null)
 *
 * @uses \Jiejunf\Resourceful\ResourceHelper::currentResourceName()
 * @uses \Jiejunf\Resourceful\ResourceHelper::getActionMethod()
 * @uses \Jiejunf\Resourceful\ResourceHelper::serviceConfig()
 * @see  ResourceHelper
 */
class Resourceful
{
    public static function __callStatic($name, $arguments)
    {
        return self::helper()->$name(...$arguments);
    }

    /**
     * @return mixed
     */
    private static function helper(): ResourceHelper
    {
        return resolve(ResourceHelper::class);
    }
}
