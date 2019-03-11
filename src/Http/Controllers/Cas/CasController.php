<?php

namespace Laravolt\Auth\Http\Controllers\Cas;

abstract class CasController
{
    /**
     * CasController constructor.
     */
    public function __construct()
    {
        $validateUrl = sprintf(
            "https://%s:%s%s/p3/serviceValidate",
            config('cas.cas_hostname'),
            config('cas.cas_port'),
            config('cas.cas_uri')
        );

        cas()->setServerServiceValidateURL($validateUrl);

        cas()->setFixedServiceURL(route('auth::cas.login'));
    }
}
