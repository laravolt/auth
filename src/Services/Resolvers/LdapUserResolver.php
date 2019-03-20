<?php

namespace Laravolt\Auth\Services\Resolvers;

use Adldap\AdldapInterface;
use Adldap\Query\Builder;

class LdapUserResolver
{
    protected $ldap;

    /**
     * LdapUserResolver constructor.
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    public function resolve($username)
    {
        $discover = config('ldap_auth.usernames.ldap.discover');
        $dn = sprintf(env('LDAP_AUTH_FORMAT'), $username);

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

        return $ldapUser;
    }
}
