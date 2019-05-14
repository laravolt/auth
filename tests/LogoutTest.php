<?php

namespace Laravolt\Auth\Tests;

use Illuminate\Support\Facades\Route;
use Laravolt\Auth\Tests\Dummy\User;

class LogoutTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::get('login-success', function () {
            return 'login success';
        });

        Route::get('logout-success', function () {
            return 'logout success';
        });
    }

    /**
     * @test
     */
    public function it_can_redirect_to_custom_url()
    {
        $this->app['config']->set('laravolt.auth.redirect.after_logout', 'logout-success');

        $this->actingAs(User::first());

        $this->visitRoute('auth::logout')
             ->seePageIs('logout-success');
    }
}
