<?php

return [
    [
        'key' => 'printful',          // uniquely defined key for menu-icon
        'name' => 'printful',        //  name of menu-icon
        'route' => 'admin.printful.index',  // the route for your menu-icon
        'sort' => 5,                    // Sort number on which your menu-icon should display
        'icon-class' => 'printful-icon',   //class of menu-icon
    ],[
        'key'        => 'printful.orders',
        'name'       => 'printful.orders',
        'route'      => 'admin.printful.index',
        'sort'       => 2,
        'icon-class' => '',
    ], [
        'key'        => 'printful.default',
        'name'       => 'printful.default',
        'route'      => 'admin.printful.index',
        'sort'       => 1,
        'icon-class' => '',
    ],
];