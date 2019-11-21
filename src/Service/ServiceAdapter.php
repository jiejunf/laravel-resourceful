<?php


namespace Jiejunf\Resourceful\Service;


use Jiejunf\Resourceful\BaseAdapter;
use Jiejunf\Resourceful\Model\ModelAdapter;
use Jiejunf\Resourceful\Resourceful;

/**
 * Class ServiceAdapter
 * @package Resourceful\Service
 * @mixin BaseService
 */
class ServiceAdapter extends BaseAdapter
{
    public function __construct()
    {
        $serviceClass = self::getServiceClass();
        $this->adapter = $this->resolveService($serviceClass);
    }

    private static function getServiceClass()
    {
        return '\\App\\Service\\' . Resourceful::currentResourceName();
    }

    private function resolveService(string $serviceClass)
    {
        if (class_exists($serviceClass)) {
            return new $serviceClass();
        }
        return new BaseService(new ModelAdapter());
    }
}
