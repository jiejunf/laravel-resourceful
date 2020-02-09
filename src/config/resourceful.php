<?php

use Jiejunf\Resourceful\Model\ResourceModel;

return [
    /* ============================================================
     * 	null : read from \App\Providers\RouteServiceProvider::$namespace
     * ============================================================
     */
    'controller_namespace' => null,

    'model_namespace' => '\\App',

    'base_model' => ResourceModel::class,

    'request_namespace' => '\\App\\Http\\Requests',
];
