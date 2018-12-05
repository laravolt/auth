<?php

namespace Laravolt\Auth\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ShouldActivate
{
    public function notify(Model $user, string $token);

    public function activate(string $token);
}