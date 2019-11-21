<?php


namespace Jiejunf\Resourceful;


use Illuminate\Support\Facades\Facade;

/**
 * Class Resourceful
 * @package Resourceful
 * @method static string currentResourceName()
 * @method static string getActionMethod()
 *
 * @uses \Jiejunf\Resourceful\ResourceHelper::currentResourceName()
 * @uses \Jiejunf\Resourceful\ResourceHelper::getActionMethod()
 * @see ResourceHelper
 */
class Resourceful extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ResourceHelper::class;
    }
}
