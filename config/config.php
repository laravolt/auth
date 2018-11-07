<?php

return [
    'layout'       => 'ui::layouts.auth',
    'captcha'      => false,
    'identifier'   => 'email',
    'registration' => [
        'enable'         => true,
        'status'         => 'ACTIVE',
        'implementation' => \Laravolt\Auth\DefaultUserRegistrar::class,
    ],
    'activation'   => [
        'enable'        => false,
        'status_before' => 'PENDING',
        'status_after'  => 'ACTIVE',
    ],
    'router'       => [
        'middleware' => ['web'],
        'prefix'     => 'auth',
    ],
    'redirect'     => [
        'after_login'          => '/',
        'after_reset_password' => '/',
    ],
];
