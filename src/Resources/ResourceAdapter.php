<?php


namespace Jiejunf\Resourceful\Resources;

use Illuminate\Http\Resources\Json\{JsonResource, ResourceCollection};
use Illuminate\Support\Str;
use Jiejunf\Resourceful\Resourceful;

class ResourceAdapter
{
    /**
     * @param mixed $resources
     * @return ResourceCollection
     */
    public static function collection($resources)
    {
        return self::resolveCollectionResponse($resources);
    }

    /**
     * @param $resources
     * @return ResourceCollection
     */
    private static function resolveCollectionResponse($resources): ResourceCollection
    {
        $actionResourceClass = self::getActionResourceClass();
        if (class_exists($actionResourceClass)) {
            return new $actionResourceClass($resources);
        }
        $rewriteClass = self::getResourceClass();
        if (class_exists($collectionRewriteClass = $rewriteClass . 'Collection')) {
            return new $collectionRewriteClass($resources);
        }
        return JsonResource::collection($resources);
    }

    private static function getActionResourceClass()
    {
        return self::getNamespace() . Str::studly(Resourceful::getActionMethod() . '-' . Resourceful::currentResourceName());
    }

    /**
     * @return string
     */
    private static function getNamespace(): string
    {
        return Str::finish(config('resourceful.resource_namespace'), '\\');
    }

    private static function getResourceClass()
    {
        return self::getNamespace() . Str::studly(Resourceful::currentResourceName());
    }

    /**
     * @param mixed $resource
     * @return JsonResource
     */
    public static function make($resource)
    {
        return self::resolveResourceResponse($resource);
    }

    /**
     * @param $resource
     * @return JsonResource
     */
    private static function resolveResourceResponse($resource): JsonResource
    {
        $actionResourceClass = self::getActionResourceClass();
        if (class_exists($actionResourceClass)) {
            return new $actionResourceClass($resource);
        }
        $rewriteClass = self::getResourceClass();
        if (class_exists($rewriteClass)) {
            return new $rewriteClass($resource);
        }
        return new JsonResource($resource);
    }
}
