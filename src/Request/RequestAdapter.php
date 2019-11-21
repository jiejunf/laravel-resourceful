<?php


namespace Jiejunf\Resourceful\Request;


use Illuminate\Support\Str;
use Jiejunf\Resourceful\BaseAdapter;
use Jiejunf\Resourceful\Resourceful;

/**
 * Class RequestAdapter
 * @package Resourceful\Request
 * @mixin BaseRequest
 */
class RequestAdapter extends BaseAdapter
{
    public function __construct()
    {
        $requestClass = RequestAdapter::getRequestClass();
        $this->adapter = $this->resolveRequest($requestClass);
    }

    public function resolveRequest($requestClass)
    {
        return class_exists($requestClass) ? resolve($requestClass) : resolve(BaseRequest::class);
    }

    public static function getRequestClass(): string
    {
        // todo : config
        return '\\App\\Http\\Requests\\' . Str::studly(Resourceful::getActionMethod() . '-' . Resourceful::currentResourceName());
    }
}
