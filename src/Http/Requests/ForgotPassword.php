<?php

namespace Laravolt\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgotPassword extends FormRequest
{
    public function rules()
    {
        return [
            'email' => ['email', 'required', 'exists:users,email'],
        ];
    }
}
