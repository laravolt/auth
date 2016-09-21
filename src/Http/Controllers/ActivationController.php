<?php

namespace Laravolt\Auth\Http\Controllers;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Routing\Controller;
use Laravolt\Auth\Activation;

class ActivationController extends Controller
{
    use Activation, RegistersUsers {
        Activation::register insteadof RegistersUsers;
    }
}
