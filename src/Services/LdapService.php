<?php

namespace Laravolt\Auth\Services;

use Adldap\Laravel\Facades\Adldap;

class LdapService
{
    protected $ldap;

    /**
     * LdapService constructor.
     */
    public function __construct()
    {
        $config = $this->config();

        $ldap = new \Adldap\Adldap();
        $ldap->addProvider($config);
        $this->ldap = $ldap->connect();
    }

    public function getUser($username, $password)
    {
        $userdn = sprintf(env('ADLDAP_LOGIN_FORMAT'), $username);
        $loginPassed = $this->ldap->auth()->attempt($userdn, $password);
        // dump('Auth attemp passed: '.$loginPassed);
        if (!$loginPassed) {
            throw new \Exception('Wrong username or password');
        }

        $ldapUser = Adldap::search()->where('userPrincipalName', '=', $username)->first();
        if (!$ldapUser) {
            throw new \Exception('Cannot find LDAP user with uid = '.$username);
        }

        $localUser = (app(config('auth.providers.users.model')))->where('ldap_username', '=', $username)->first();
        if (!$localUser) {
            throw new \Exception('LDAP user exists, but does not have a corresponding local account');
        }

        $savedInformation = $localUser->toArray();
        unset($savedInformation['ldap_information']);

        $localUser->ldap_information = $savedInformation;
        $localUser->save();

        return $localUser;
    }

    protected function config()
    {
        return [
            'account_suffix'       => env('ADLDAP_ACCOUNT_SUFFIX'),
            'domain_controllers'   => [env('ADLDAP_CONTROLLERS')],
            'base_dn'              => env('ADLDAP_BASEDN'),
            'admin_username'       => env('ADLDAP_ADMIN_USERNAME'),
            'admin_password'       => env('ADLDAP_ADMIN_PASSWORD'),
            'admin_account_suffix' => env('ADLDAP_ADMIN_ACCOUNT_SUFFIX'),
        ];
    }
}
