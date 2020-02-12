<?php

namespace Jiejunf\Resourceful;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Jiejunf\Resourceful\Controller\ResourceController;

class RouteGenerator
{
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
        foreach ($resources as $resource => $subOptions) {
            if (!is_array($subOptions)) {
                $resource = $subOptions;
                $subOptions = [];
            }
            $subOptions += $options;
            self::registerRoute($resource, $subOptions);
        }
    }

    /**
     * @param $options
     * @param string $resource
     */
    protected static function registerRoute(string $resource, $options): void
    {
        $controller = self::makeControllerClass($resource);
        Route::apiResource($resource, $controller, $options);
    }

    /**
     * @param string $resource
     * @return string
     */
    protected static function makeControllerClass(string $resource): string
    {
        $controller = Str::singular(Str::studly($resource)) . "Controller";
        if (self::controllerCheck($controller)) {
            return $controller;
        }
        return Str::start(ResourceController::class, '\\');
    }

    protected static function controllerCheck(string $controller): bool
    {
        return class_exists(self::getNamespace() . $controller);
    }

    protected static function getNamespace(): string
    {
        $config = config('resourceful.controller_namespace');
        if (is_null($config)) {
            $config = (function () {
                return $this->namespace;
            })->call(new RouteServiceProvider(app()));
        }
        return Str::finish($config, '\\');
    }
}
