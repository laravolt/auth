<?php

namespace Laravolt\Auth\Contracts;

interface ForgotPassword
{
    public function getUserByIdentifier($identifier);
}
