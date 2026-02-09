<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|min:8|string'
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'email must be provided',
            'email.email' => 'invalied email format',
            'otp.required' => 'otp must be provided',
            'password.required' => 'password should be given',
            'password.string' => 'password should be string',
            'password.min' => 'password should be at least 8 characters',
        ];
    }
}
