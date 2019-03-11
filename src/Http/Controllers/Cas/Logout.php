<?php

namespace Laravolt\Auth\Http\Controllers\Cas;

class Logout extends CasController
{
    public function __invoke()
    {
        cas()->logout();
    }
}
