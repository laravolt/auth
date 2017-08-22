<?php

namespace Laravolt\Auth\Tests;

class ForgotPasswordTest extends TestCase
{

    /**
     * @test
     */
    public function it_can_display_forgot_password_page()
    {
        $this->visitRoute('auth::forgot');
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_has_forgot_password_form()
    {
        $this->visitRoute('auth::forgot')
             ->seeElement('input[name=email]');
    }

    /**
     * @test
     */
    public function it_can_handle_correct_email()
    {
        $this->visitRoute('auth::forgot')
            ->type('jon@dodo.com', 'email')
            ->press(trans('auth::auth.send_reset_password_link'))
            ->seeRouteIs('auth::forgot');
    }

    /**
     * @test
     */
    public function it_can_handle_wrong_email()
    {
        $this->visitRoute('auth::forgot')
             ->type('jon@dodo.com', 'email')
             ->press(trans('auth::auth.send_reset_password_link'))
             ->seeRouteIs('auth::forgot');
    }

    /**
     * @test
     */
    public function it_redirect_back_if_failed()
    {
        $this->visitRoute('auth::forgot')
             ->type('invalid-email-format', 'email')
             ->press(trans('auth::auth.send_reset_password_link'))
             ->seeRouteIs('auth::forgot');
    }

    /**
     * @test
     */
    public function it_has_errors_if_failed()
    {
        $this->post(route('auth::forgot'))->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function it_has_register_link()
    {
        $this->get(route('auth::forgot'))->seeText(trans('auth::auth.register_here'));
    }

    /**
     * @test
     */
    public function it_does_not_have_register_link_if_registration_disabled()
    {
        $this->app['config']->set('laravolt.auth.registration.enable', false);
        $this->get(route('auth::forgot'))->dontSeeText(trans('auth::auth.register_here'));
    }
}
