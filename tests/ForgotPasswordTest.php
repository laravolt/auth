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
    public function it_can_validate_form()
    {
        $this->visitRoute('auth::forgot')
             ->type('invalid-email-format', 'email')
             ->press(trans('auth::auth.send_reset_password_link'))
            //@todo: assert error message present
             ->seeRouteIs('auth::forgot');
    }

}
