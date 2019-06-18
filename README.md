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
* Optionally, you can run `php artisan vendor:publish --provider="Laravolt\Auth\ServiceProvider" --tag="migrations"` to publish migrations files for further editing


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

    // Configuration related to login process
    'login' => [
        'implementation' => \Laravolt\Auth\DefaultLogin::class,
    ],

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

        // Where to redirect after successfully register
        'after_register'       => '/',

        // Where to redirect after successfully reset password
        'after_reset_password' => '/',
    ],

    // Whether to auto load migrations or not.
    // If set to false, then you must publish the migration files first before running the migrate command
    'migrations' => false,
];
```

## Captcha
If you enable captcha (by setting `'captcha' => true` in config file), please add following entries to `.env`:
```
NOCAPTCHA_SECRET=YOUR_RECAPTCHA_SECRET
NOCAPTCHA_SITEKEY=YOUR_RECAPTCHA_SITEKEY
```
You can obtain them from www.google.com/recaptcha/admin.

## Custom Login Form

### Modify Form (View File)
Run `php artisan vendor:publish --provider="Laravolt\Auth\ServiceProvider"`.
You can modify the view located in `resources/views/vendor/auth/login.blade.php`.

### Modify Logic
Create new class to handle user login that implements `Laravolt\Auth\Contracts\Login` contract.
You must implement two method related to registration:
1. `rules(Request $request)` to get validation rules.
2. `credentials(Request $request)` to check valid credentials.
optionally:
1. `authenticated(Request $request, $user)` to handle after login, it should be returned `\Illuminate\Http\Response` or `null`
1. `failed(Request $request)` to handle custom failed response

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
2. `register($data)` to handle user creation logic.
optionally:
1. `registered(Request $request, $user)` to handle after registration is completed, it should be returned `\Illuminate\Http\Response` or `null`

```php
<?php
namespace App\Registration;

use Illuminate\Support\Facades\Validator;
use Laravolt\Auth\Contracts\UserRegistrar;

class CustomUserRegistrar implements UserRegistrar
{
    /**
     * Validate data.
     *
     * @param array $data
     */
    public function validate(array $data)
    {
        // Modify default behaviour, or completely change it
        return Validator::make(
            $data,
            [
                'name'     => 'required|max:255',
                'email'    => 'required|email|max:255|unique:users',
                'password' => 'required|min:6',
            ]
        );
    }

    /**
     * Create model.
     *
     * @param $
     *
     */
    public function register(array $data)
    {
        // create Authenticatable model.
        $user = User::create($data);

        // return Authenticatable model.
        return $user;
    }
}

```

#### Modify Activation Logic

add `Laravolt\Auth\Contracts\ShouldActivate` implementation to your `registration.implementation` class by add these function to your registrar class.
1. `notify(Model $user, $token)`
2. `activate($token)`
```php
...
class CustomUserRegistrar implements UserRegistrar, ShouldActivate
{
    ...

    /**
     * Notify if user to activate the user with the token provided.
     *
     * @param \Illuminate\Database\Eloquent\Model|Authenticatable $user
     * @param string $token
     * @return void
     */
    public function notify(Model $user, $token)
    {
        //
    }

    /**
     * Activation method by the token provided.
     *
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function activate($token)
    {
        $token = \DB::table('users_activation')->whereToken($token)->first();

        if (! $token) {
            abort(404);
        }

        \User::where('id', $token->user_id)->update(['status' => config('laravolt.auth.activation.status_after')]);
        \DB::table('users_activation')->where('user_id', $token->user_id)->delete();

        return redirect()->route('auth::login')->withSuccess(trans('auth::auth.activation_success'));
    }
}
```

After that, you must update auth config (located in `config/laravolt/auth.php`, if not, just run `php artisan vendor:publish`).

```php
...
    'registration' => [
        // During the process, data from registration form will be passed to this class.
        // You may create your own implementation by creating UserRegistrar class.
        'implementation' => \App\Registration\CustomUserRegistrar::class,
    ],

...
```

## LDAP

### Environment Variables
```
LDAP_HOSTS=ldap.forumsys.com
LDAP_BASE_DN='dc=example,dc=com'
LDAP_PORT=389
LDAP_USERNAME='cn=read-only-admin,dc=example,dc=com'
LDAP_PASSWORD='password'
LDAP_USE_SSL=false
LDAP_USE_TLS=false
```
