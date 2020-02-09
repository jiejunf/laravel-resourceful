<?php


namespace Jiejunf\Resourceful\Request;


use Illuminate\Support\Str;
use Jiejunf\Resourceful\BaseAdapter;
use Jiejunf\Resourceful\Resourceful;

/**
 * Class RequestAdapter
 * @package Resourceful\Request
 * @mixin ResourceRequest
 */
class RequestAdapter extends BaseAdapter
{
    public function __construct()
    {
        $this->adapter = $this->resolveRequest();
    }

    public function resolveRequest()
    {
        $rewriteClass = RequestAdapter::getRequestClass();
        return class_exists($rewriteClass) ? resolve($rewriteClass) : resolve(ResourceRequest::class);
    }

    public static function getRequestClass(): string
    {
        return self::getNamespace() . Str::studly(Resourceful::getActionMethod() . '-' . Resourceful::currentResourceName());
    }

    /**
     * @return string
     */
    protected static function getNamespace(): string
    {
        return Str::finish(config('resourceful.request_namespace'), '\\');
    }
}
