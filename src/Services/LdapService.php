<?php

namespace Laravolt\Auth\Services;

use Adldap\AdldapInterface;
use Adldap\Query\Builder;
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
        $dn = sprintf(env('LDAP_AUTH_FORMAT'), $username);

        $loginPassed = $this->ldap->auth()->attempt($dn, $password);

        if (!$loginPassed) {
            throw new \Exception('LDAP authentication failed');
        }

        $discover = config('ldap_auth.usernames.ldap.discover');
        $ldapUser = $this->ldap->search()->orFilter(
            function (Builder $builder) use ($discover, $username, $dn) {
                $builder
                    ->where($discover, '=', $username)
                    ->where($discover, '=', $dn);
            }
        )->first();

        if (!$ldapUser) {
            throw new \Exception(sprintf('Cannot find LDAP user with %s = %s or %s', $discover, $username, $dn));
        }

        $localUser = User::where(config('ldap_auth.usernames.eloquent'), '=', $username)->first();
        if (!$localUser) {
            throw new \Exception('LDAP user exists, but does not have a corresponding eloquent record');
        }

        return $localUser;
    }
}
