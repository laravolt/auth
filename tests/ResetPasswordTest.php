<?php

namespace Laravolt\Auth\Tests;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Laravolt\Auth\Tests\Dummy\User;

class ResetPasswordTest extends TestCase
{
    protected $email = 'andi@laravolt.com';

    protected $token;

    protected $table;


    public function setUp()
    {
        parent::setUp();

        $this->table = config('auth.passwords.users.table');

        $this->createPasswordResetsTable();

        $user = User::whereEmail($this->email)->first();
        $this->token = app('auth.password.broker')->createToken($user);
    }

    /**
     * @test
     */
    public function it_can_display_page()
    {
        $this->visitRoute('auth::reset', 'asdf1234');
        $this->assertResponseOk();
    }

    /**
     * @test
     */
    public function it_has_correct_form_field()
    {
        $this->visitRoute('auth::reset', 'asdf1234')
             ->seeElement('input[name=email]')
             ->seeElement('input[name=password]')
             ->seeElement('input[name=password_confirmation]');
    }

    /**
     * @test
     */
    public function it_can_reset_password()
    {
        Route::get('reset-password-success', function () {
            return 'login success';
        });

        $this->visitRoute('auth::reset', $this->token)
             ->type($this->email, 'email')
             ->type('1nd0n351a r4y4', 'password')
             ->type('1nd0n351a r4y4', 'password_confirmation')
             ->press(trans('auth::auth.reset_password'))
             ->seePageIs('reset-password-success');

        $this->seeIsAuthenticated();
    }

    /**
     * @test
     */
    public function it_redirect_back_if_failed()
    {
        $this->visitRoute('auth::reset', 'asdf1234')
             ->type('invalid-email-format', 'email')
             ->press(trans('auth::auth.reset_password'))
             ->seeRouteIs('auth::reset', 'asdf1234');
    }

    /**
     * @test
     */
    public function it_has_errors_if_failed()
    {
        $this->post(route('auth::reset', 'asdf1234'))->assertSessionHasErrors();
    }

    protected function createPasswordResetsTable()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create($this->table, function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable();
        });
    }
}
