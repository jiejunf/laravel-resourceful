<?php


namespace Jiejunf\Resourceful;


use Illuminate\Support\ServiceProvider;

class ResourcefulProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/resourceful.php' => config_path('resourceful.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/resourceful.php', 'resourceful');
    }
}
