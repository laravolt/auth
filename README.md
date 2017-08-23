# laravolt/auth

![https://travis-ci.org/laravolt/auth](https://img.shields.io/travis/laravolt/auth.svg)
![https://coveralls.io/github/laravolt/auth](https://img.shields.io/coveralls/laravolt/auth.svg)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/64a4da48-4cab-418e-9594-cb90d7f3e792/mini.png)](https://insight.sensiolabs.com/projects/64a4da48-4cab-418e-9594-cb90d7f3e792)

Laravel authentication with some additional features:

* Activation
* Enable/disable registration
* Captcha
* Custom email template
* Functionally tested


## Installation

* Run `composer require laravolt/auth`
* Register `Laravolt\Auth\ServiceProvider::class` as service providers

## Configuration
```php
<?php
return [
    'layout'       => 'auth::layout',
    'captcha'      => false,
    'registration' => [
        'enable' => true,
        'status' => 'ACTIVE'
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
    'redirect'    => [
        'after_login' => '/',
        'after_reset_password' => '/',
    ],
];
```
