<?php

namespace Laravolt\Auth\Tests;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Laravolt\Auth\Mail\ActivationMail;

class RegisterTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        Route::get('login-success', function(){
            return 'Login success';
        });
    }

    /**
     * @test
     */
    public function it_can_display_register_page()
    {
        $this->get(route('auth::register'));
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_has_registration_form()
    {
        $this->get(route('auth::register'))
            ->seeElement('input[name=name]')
            ->seeElement('input[name=email]')
            ->seeElement('input[name=password]');

    }

    /**
     * @test
     */
    public function it_can_handle_correct_registration()
    {
        $this->app['config']->set('laravolt.auth.redirect.after_login', '/login-success');

        $this->visitRoute('auth::register')
             ->type('Jon Dodo', 'name')
             ->type('jon@dodo.com', 'email')
             ->type('asdf1234', 'password')
             ->press(trans('auth::auth.register'))
             ->seePageIs(config('laravolt.auth.redirect.after_login'));
    }

    /**
     * @test
     */
    public function it_can_handle_correct_registration_with_activation()
    {
        $this->app['config']->set('laravolt.auth.activation.enable', true);

        Mail::fake();
        $email = 'jon@dodo.com';

        $this->visitRoute('auth::register')
             ->type('Jon Dodo', 'name')
             ->type($email, 'email')
             ->type('asdf1234', 'password')
             ->press(trans('auth::auth.register'))
             ->seeRouteIs('auth::register');

        Mail::assertSentTo($email, ActivationMail::class);
    }

    /**
     * @test
     */
    public function it_can_validate_registration()
    {
        $this->visitRoute('auth::register')
            ->type('', 'name')
            ->type('', 'email')
            ->type('', 'password')
            ->press(trans('auth::auth.register'))
            //@todo: assert error message present
            ->seeRouteIs('auth::register');
    }

}
