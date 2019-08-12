<?php

namespace Laravolt\Auth\Tests;

use Anhskohbo\NoCaptcha\NoCaptchaServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Laravolt\Auth\ServiceProvider as AuthServiceProvider;
use Laravolt\Auth\Tests\Dummy\User;
use Laravolt\Password\ServiceProvider;
use Laravolt\Ui\ServiceProvider as UiServiceProvider;
use Orchestra\Database\ConsoleServiceProvider;
use Orchestra\Testbench\BrowserKit\TestCase as BaseTestCase;
use Stolz\Assets\Laravel\ServiceProvider as AssetsServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getDatabasePath()
    {
        return ':memory:';
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            AuthServiceProvider::class,
            NoCaptchaServiceProvider::class,
            ConsoleServiceProvider::class,
            UiServiceProvider::class,
            AssetsServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => $this->getDatabasePath(),
            'prefix'   => '',
        ]);

        $app['config']->set('auth.providers.users.model', User::class);
        $app['config']->set('laravolt.auth.redirect.after_login', '/login-success');
        $app['config']->set('laravolt.auth.redirect.after_reset_password', '/reset-password-success');
        $app['config']->set('session.expire_on_close', false);

        $app['view']->addNamespace('dummy', __DIR__.'/Dummy');
        $app['config']->set('laravolt.auth.layout', 'dummy::layout');
    }

    protected function setUpDatabase()
    {
        $this->createUserTable();

        $this->loadMigrationsFrom(realpath(__DIR__.'/../database/migrations'));

        User::create(['name' => 'Andi', 'email' => 'andi@laravolt.com', 'password' => bcrypt('asdf1234')]);
    }

    protected function createUserTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('password_last_set')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
