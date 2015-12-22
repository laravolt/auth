<?php
/*
 * Set specific configuration variables here
 */
return [
    // automatic loading of routes through main service provider
    'routes'   => true,
    'layout'   => 'auth::auth.layout',
    'services' => ['facebook', 'twitter', 'google', 'linkedin', 'github'],
    'default'  => [
        'status' => 'ACTIVE'
    ]
];
