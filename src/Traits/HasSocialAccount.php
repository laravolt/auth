<?php
namespace Laravolt\Auth\Traits;

use Laravolt\Auth\Models\SocialAccount;


trait HasSocialAccount
{

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

}
