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

        Route::get('login-success', function () {
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
        $this->app['config']->set('laravolt.auth.activation.enable', false);
        $this->app['config']->set('laravolt.auth.redirect.after_login', '/login-success');

        $name = 'Jon Dodo';
        $email = 'jon@example.com';
        $status = $this->app['config']->get('laravolt.auth.registration.status');

        $this->visitRoute('auth::register')
             ->type($name, 'name')
             ->type($email, 'email')
             ->type('asdf1234', 'password')
             ->press(trans('auth::auth.register'))
             ->seePageIs(config('laravolt.auth.redirect.after_login'))
             ->seeInDatabase('users', [
                 'name'   => $name,
                 'email'  => $email,
                 'status' => $status,
             ]);
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

        Mail::assertSent(ActivationMail::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    /**
     * @test
     */
    public function it_redirect_back_if_failed()
    {
        $this->visitRoute('auth::register')
            ->type('', 'name')
            ->type('', 'email')
            ->type('', 'password')
            ->press(trans('auth::auth.register'))
            ->seeRouteIs('auth::register');
    }

    /**
     * @test
     */
    public function it_has_errors_if_failed()
    {
        $this->post(route('auth::register'))
            ->assertSessionHasErrors();
    }
}
