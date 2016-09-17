<?php

namespace Laravolt\Auth\Tests;

use Illuminate\Support\Facades\Route;

class LoginTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        Route::get('login-success', function(){
            return 'login success';
        });
    }

    /**
     * @test
     */
    public function it_can_display_login_page()
    {
        $this->get(route('auth::login'));
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_can_handle_correct_login()
    {
        $this->visitRoute('auth::login')
             ->type('andi@laravolt.com', 'email')
             ->type('asdf1234', 'password')
             ->press('Login')
             ->seePageIs(config('laravolt.auth.redirect.after_login'));
    }

    /**
     * @test
     */
    public function it_can_handle_wrong_login()
    {
        $this->visitRoute('auth::login')
            ->type('wrong@email.com', 'email')
            ->type('wrongpassword', 'password')
            ->press('Login')
            ->seeRouteIs('auth::login');
    }

    /**
     * @test
     */
    public function it_can_validate_empty_login()
    {
        $this->visitRoute('auth::login')
            ->type('', 'email')
            ->type('', 'password')
            ->press('Login')
            //@todo: assert error message present
            ->seeRouteIs('auth::login');
    }
}
