<?php

namespace Laravolt\Auth\Services\Resolvers;

class EloquentUserResolver
{
    public function resolve($username)
    {
        $column = config('ldap_auth.usernames.eloquent');
        $user = app(config('auth.providers.users.model'))->where($column, '=', $username)->first();

        if (!$user) {
            throw new \Exception(sprintf('Cannot find eloquent User with %s = %s', $column, $username));
        }

        return $user;
    }
}
