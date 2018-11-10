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
* For Laravel 5.4 or below, add `Laravolt\Auth\ServiceProvider::class` as service providers

## Configuration

```php
<?php
return [
    // Base layout to extend by every view
    'layout'       => 'ui::layouts.auth',
    
    // Enable captcha (Google reCaptcha) on login form
    'captcha'      => false,

    // Column name to be checked for authentication (login)
    'identifier'   => 'email',

    // Configuration related to registration process
    'registration' => [
        
        // Enable or disable registration form
        'enable'         => true,
        
        // Default status for newly registered user        
        'status'         => 'ACTIVE',
        
        // During the process, data from registration form will be passed to this class.
        // You may create your own implementation by creating UserRegistrar class.
        'implementation' => \Laravolt\Auth\DefaultUserRegistrar::class,
    ],

    // Configuration related to registration process
    'activation'   => [
        // If enabled, newly registered user are not allowed to login until they click
        // activation link that sent to their email.
        'enable'        => false,
        
        // Status for newly registered user, before activation        
        'status_before' => 'PENDING',
        
        // Status for newly registered user, after successfully activate their account
        'status_after'  => 'ACTIVE',
    ],

    // Routes configuration
    'router'       => [
        'middleware' => ['web'],
        'prefix'     => 'auth',
    ],

    // Redirect configuration
    'redirect'     => [
        // Where to redirect after successfully login
        'after_login'          => '/',
        
        // Where to redirect after successfully reset password
        'after_reset_password' => '/',
    ],
];
```

## Captcha
If you enable captcha (by setting `'captcha' => true` in config file), please add following entries to `.env`:
```
NOCAPTCHA_SECRET=YOUR_RECAPTCHA_SECRET
NOCAPTCHA_SITEKEY=YOUR_RECAPTCHA_SITEKEY
```
You can obtain them from www.google.com/recaptcha/admin.

## Custom Registration Form
Sometimes you need to modify registration form, e.g. add more fields, change logic, or add some validation.
There are several way you can accomplish those.

### Modify Form (View File)
Run `php artisan vendor:publish --provider="Laravolt\Auth\ServiceProvider"`. 
You can modify the view located in `resources/views/vendor/auth/register.blade.php`.

### Modify Logic
Create new class to handle user registration that implements `Laravolt\Auth\Contracts\UserRegistrar` contract.
You must implement two method related to registration:
1. `validate($data)` to handle validation logic.
1. `register($data)` to handle user creation logic.

```php
<?php
namespace App\Registration;

use Illuminate\Support\Facades\Validator;
use Laravolt\Auth\Contracts\UserRegistrar;

class CustomUserRegistrar implements UserRegistrar
{
    public function validate(array $data)
    {
        // Modify default behaviour, or completely change it
        // return Validator::make(
        //     $data,
        //     [
        //         'name'     => 'required|max:255',
        //         'email'    => 'required|email|max:255|unique:users',
        //         'password' => 'required|min:6',
        //     ]
        // );
    }

    public function register(array $data)
    {
        $user = new User();
        
        // anything else here
        
        // Just make sure to return $user (Authenticable)
        return $user;
    }
}

```
