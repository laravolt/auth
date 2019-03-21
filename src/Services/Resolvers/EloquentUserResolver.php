<?php

namespace Laravolt\Auth\Services\Resolvers;

use Adldap\Models\User;

class EloquentUserResolver
{
    public function resolve(User $ldapUser, $data)
    {
        $username = $ldapUser->getAttribute('uid', 0);
        $column = config('ldap_auth.usernames.eloquent');
        $user = app(config('auth.providers.users.model'))->where($column, '=', $username)->first();

        if (!$user) {
            throw new \Exception(sprintf('Cannot find eloquent User with %s = %s', $column, $username));
        }

        return $user;
    }
}
