<?php


namespace Jiejunf\Resourceful\Resources;

use Illuminate\Http\Resources\Json\{JsonResource, ResourceCollection};
use Illuminate\Support\Str;
use Jiejunf\Resourceful\Resourceful;

class ResourceAdapter
{
    /**
     * @param mixed $resource
     * @return ResourceCollection
     */
    public static function collection($resource)
    {
        $collectionResponse = self::resolveCollectionResponse($resource);
        self::withData($collectionResponse);
        return $collectionResponse;
    }

    /**
     * @param mixed ...$parameters
     * @return JsonResource
     */
    public static function make(...$parameters)
    {
        $resourceResponse = self::resolveResourceResponse($parameters);
        self::withData($resourceResponse);
        return $resourceResponse;
    }

    private static function getResourceClass(string $resource_name)
    {
        return '\\App\\Http\\Resources\\' . Str::studly($resource_name);
    }

    /**
     * @param $resource
     * @return ResourceCollection
     */
    private static function resolveCollectionResponse($resource): ResourceCollection
    {
        $resourceClass = self::getResourceClass(Resourceful::currentResourceName());
        if (class_exists($collectionClass = $resourceClass . 'Collection')) {
            /** @var ResourceCollection $collectionClass */
            return new $collectionClass($resource);
        }
        if (class_exists($resourceClass)) {
            /** @var JsonResource $resourceClass */
            return $resourceClass::collection($resource);
        }
        return JsonResource::collection($resource);
    }

    /**
     * @param $parameters
     * @return JsonResource
     */
    private static function resolveResourceResponse($parameters): JsonResource
    {
        $resourceClass = self::getResourceClass(Resourceful::currentResourceName());
        if (class_exists($resourceClass)) {
            /** @var JsonResource $resourceClass */
            return $resourceClass::make(...$parameters);
        }
        return JsonResource::make(...$parameters);
    }

    /**
     * @param JsonResource $resourceResponse
     */
    private static function withData(JsonResource $resourceResponse): void
    {
        // todo : config
        $resourceResponse->with = [
            'result' => 'success',
        ];
    }
}
