<?php

use Jiejunf\Resourceful\Model\ResourceModel;

return [
    /* ============================================================
     * 	null : read from \App\Providers\RouteServiceProvider::$namespace
     * ============================================================
     */
    'controller_namespace' => null,

    'model_namespace' => '\\App',

    'request_namespace' => '\\App\\Http\\Requests',

    'resource_namespace' => '\\App\\Http\\Resources',

    'base_model' => ResourceModel::class,


    'services' => [
        /* ============================================================
         *  - $singular-resource_name
         *      - `array`   sort_default    : array("column1" => "asc|desc",...)
         *      - `array`   sortables       : array("column1","column2",...)
         *      - `array`   hidden          : array("column1","column2",...)
         *      - `array`   searchable      : array("column1","column2",...)
         *      - `bool`    force_paginate
         *      - `int`     per_page_default
         *  ...
         * ============================================================
         */
        'sample' => [
            'sort_default' => ['created_at' => 'desc'],
            'sortables' => ['created_at', 'updated_at', 'id'],
            'hidden' => [],
            'searchable' => ['app_type'],
            'force_paginate' => true,
            'per_page_default' => 5,
        ]
    ]
];
