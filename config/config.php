<?php
/*
 * Set specific configuration variables here
 */
return [
    // automatic loading of routes through main service provider
    'routes'         => true,
    'layout'         => 'auth::auth.layout',
    'default_status' => \Laravolt\Auth\Enum\UserStatus::PENDING
];
