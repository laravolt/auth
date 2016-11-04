<?php

namespace Laravolt\Auth\Tests;

use Illuminate\Database\Schema\Blueprint;
use Laravolt\Auth\Tests\Dummy\User;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function getDatabasePath()
    {
        //return __DIR__.'/database/auth.sqlite';
        return ':memory:';
    }

    protected function setUp()
    {
        parent::setUp();
        $this->setUpDatabase();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [
            \Laravolt\Auth\ServiceProvider::class,
            'Anhskohbo\NoCaptcha\NoCaptchaServiceProvider',
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

        //$app->make('Illuminate\Contracts\Http\Kernel')->pushMiddleware(\Illuminate\Session\Middleware\StartSession::class);

    }

    protected function setUpDatabase()
    {
        //if (!file_exists($file = $this->getDatabasePath())) {
        //    touch($file);
        //} else {
        //    $this->cleanDatabase();
        //}

        $this->createUserTable();

        $this->loadMigrationsFrom([
            '--database' => 'sqlite',
            '--realpath' => realpath(__DIR__.'/../database/migrations'),
        ]);

        $this->beforeApplicationDestroyed(function () {
            $this->cleanDatabase();
        });

        User::create(['name' => 'Andi', 'email' => 'andi@laravolt.com', 'password' => bcrypt('asdf1234')]);
    }

    protected function cleanDatabase()
    {
        //file_put_contents($this->getDatabasePath(), null);
    }

    protected function createUserTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status')->default('ACTIVE');
            $table->rememberToken();
            $table->timestamps();
        });
    }
}
