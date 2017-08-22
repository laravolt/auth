<?php

namespace Laravolt\Auth;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class PackageServiceProvider
 *
 * @package LaraLeague\Auth
 * @see     http://laravel.com/docs/master/packages#service-providers
 * @see     http://laravel.com/docs/master/providers
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register the service provider.
     *
     * @see    http://laravel.com/docs/master/providers#the-register-method
     * @return void
     */
    public function register()
    {
    }

    /**
     * Application is booting
     *
     * @see    http://laravel.com/docs/master/providers#the-boot-method
     * @return void
     */
    public function boot()
    {
        $this->registerViews();
        $this->registerMigrations();
        $this->registerTranslations();
        $this->registerConfigurations();

        if (!$this->app->routesAreCached()) {
            $this->registerRoutes();
        }

        if (config('laravolt.auth.captcha')) {
            $this->app->register('Anhskohbo\NoCaptcha\NoCaptchaServiceProvider');
        }
    }

    /**
     * Register the package views
     *
     * @see    http://laravel.com/docs/master/packages#views
     * @return void
     */
    protected function registerViews()
    {
        // register views within the application with the set namespace
        $this->loadViewsFrom($this->packagePath('resources/views'), 'auth');

        // allow views to be published to the storage directory
        $this->publishes(
            [$this->packagePath('resources/views') => base_path('resources/views/vendor/auth')],
            'views'
        );
    }

    /**
     * Register the package migrations
     *
     * @see    http://laravel.com/docs/master/packages#publishing-file-groups
     * @return void
     */
    protected function registerMigrations()
    {
        if (version_compare($this->app->version(), '5.3.0', '>=')) {
            $this->loadMigrationsFrom($this->packagePath('database/migrations'));
        } else {
            $this->publishes(
                [$this->packagePath('database/migrations') => database_path('/migrations')],
                'migrations'
            );
        }
    }

    /**
     * Register the package translations
     *
     * @see    http://laravel.com/docs/master/packages#translations
     * @return void
     */
    protected function registerTranslations()
    {
        $this->loadTranslationsFrom($this->packagePath('resources/lang'), 'auth');
    }

    /**
     * Register the package configurations
     *
     * @see    http://laravel.com/docs/master/packages#configuration
     * @return void
     */
    protected function registerConfigurations()
    {
        $this->mergeConfigFrom(
            $this->packagePath('config/config.php'),
            'laravolt.auth'
        );
        $this->publishes(
            [$this->packagePath('config/config.php') => config_path('laravolt/auth.php')],
            'config'
        );
    }

    /**
     * Register the package routes
     *
     * @warn   consider allowing routes to be disabled
     * @see    http://laravel.com/docs/master/routing
     * @see    http://laravel.com/docs/master/packages#routing
     * @return void
     */
    protected function registerRoutes()
    {
        $this->app['router']->group(
            [
                'namespace'  => __NAMESPACE__.'\Http\Controllers',
                'middleware' => config('laravolt.auth.router.middleware'),
                'prefix'     => config('laravolt.auth.router.prefix'),
                'as'         => 'auth::',
            ],
            function (Router $router) {

                // Authentication Routes...
                $router->get('login', 'LoginController@showLoginForm')->name('login');
                $router->post('login', 'LoginController@login')->name('login');
                $router->any('logout', 'LoginController@logout')->name('logout');

                // Password Reset Routes...
                $router->get('forgot', 'ForgotPasswordController@showLinkRequestForm')->name('forgot');
                $router->post('forgot', 'ForgotPasswordController@sendResetLinkEmail')->name('forgot');
                $router->get('reset/{token}', 'ResetPasswordController@showResetForm')->name('reset');
                $router->post('reset/{token}', 'ResetPasswordController@reset')->name('reset');

                if (config('laravolt.auth.registration.enable')) {
                    // Registration Routes...
                    $router->get('register', 'RegisterController@showRegistrationForm')->name('register');
                    $router->post('register', 'RegisterController@register')->name('register');

                    // Activation Routes...
                    $router->get('activate/{token}', 'ActivationController@activate')->name('activate');
                }
            }
        );
    }

    /**
     * Loads a path relative to the package base directory
     *
     * @param  string $path
     * @return string
     */
    protected function packagePath($path = '')
    {
        return sprintf("%s/../%s", __DIR__, $path);
    }
}
