<?php

return [
    'layout'       => 'ui::layouts.public.full',
    'captcha'      => false,
    'identifier'   => 'email',
    'login'        => [
        'implementation' => \Laravolt\Auth\DefaultLogin::class,
    ],
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
    'cas'          => [
        'enable' => true,
    ],
    'router'       => [
        'middleware' => ['web'],
        'prefix'     => 'auth',
    ],
    'redirect'     => [
        'after_login'          => '/',
        'after_register'       => '/',
        'after_reset_password' => '/',
    ],
];
