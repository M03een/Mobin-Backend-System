<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ForgetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'email must be given',
            'email.email' => 'invalid email format',
            'email.exists' => 'if this email exists, a reset link was sent'
        ];
    }
}
