<?php

namespace Laravolt\Auth\Services;

use Adldap\AdldapInterface;

class LdapService
{
    protected $ldap;

    protected $ldapUser;

    protected $eloquentUser;

    /**
     * LdapService constructor.
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    public function resolveUser($data)
    {
        $username = array_get($data, config('laravolt.auth.identifier'));
        $password = array_get($data, 'password');
        $dn = sprintf(env('LDAP_AUTH_FORMAT'), $username);

        $loginPassed = $this->ldap->auth()->attempt($dn, $password);

        if (!$loginPassed) {
            throw new \Exception('LDAP authentication failed');
        }

        $this->ldapUser = app(config('laravolt.auth.ldap.resolver.ldap_user'))->resolve($username);

        $this->eloquentUser = app(config('laravolt.auth.ldap.resolver.eloquent_user'))->resolve($this->ldapUser);
    }

    public function ldapUser()
    {
        return $this->ldapUser;
    }

    public function eloquentUser()
    {
        return $this->eloquentUser;
    }
}
