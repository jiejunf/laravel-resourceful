<?php

namespace Jiejunf\Resourceful;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Jiejunf\Resourceful\Controller\BaseController;

class RouteGenerator
{
    // todo : config
    const APP_CONTROLLER_NAMESPACE = '\\App\\Http\\Controllers\\';

    /**
     * @param array|string[] $resources
     * @param array $options
     * <pre>
     * > except(array|string):
     * remove http method in index,show,store,update,destroy.
     * > only(array):
     * merge http method into default.
     * </pre>
     */
    public static function resources(array $resources, $options = []): void
    {
        foreach ($resources as $resource) {
            Route::apiResource($resource, static::getControllerClass($resource), $options);
        }
    }

    public static function getControllerClass(string $resource)
    {
        $controllerClass = self::APP_CONTROLLER_NAMESPACE . "" . Str::studly($resource) . "Controller";
        return class_exists($controllerClass) ? $controllerClass : BaseController::class;
    }
}
