<?php

namespace Laravolt\Auth\Tests\Dummy;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravolt\Password\CanChangePassword;
use Laravolt\Password\CanChangePasswordContract;

class User extends Authenticatable implements CanChangePasswordContract
{
    use CanChangePassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];
}
