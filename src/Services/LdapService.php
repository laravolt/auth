<?php

namespace Laravolt\Auth\Services;

use Adldap\AdldapInterface;
use App\User;

class LdapService
{
    protected $ldap;

    /**
     * LdapService constructor.
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    public function getUser($data)
    {
        $username = array_get($data, config('laravolt.auth.identifier'));
        $password = array_get($data, 'password');
        $dn = sprintf("uid=%s,%s", $username, env('LDAP_BASE_DN'));

        $loginPassed = $this->ldap->auth()->attempt($dn, $password);

        if (!$loginPassed) {
            throw new \Exception('LDAP authentication failed');
        }

        $ldapUser = $this->ldap->search()->where(config('ldap_auth.usernames.ldap.authenticate'), '=', $username)->first();
        if (!$ldapUser) {
            throw new \Exception('Cannot find LDAP user with uid = '.$username);
        }

        $localUser = User::where(config('ldap_auth.usernames.eloquent'), '=', $username)->first();
        if (!$localUser) {
            throw new \Exception('LDAP user exists, but does not have a corresponding local account');
        }

        return $localUser;
    }
}
