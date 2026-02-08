<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(): array 
{ 
    return [
        // username
        'username.required' => 'username should be given', 
        'username.string' => 'username should be string', 
        'username.max' => 'username should be less than 255 characters', 
        'username.unique' => 'username already taken', 

        // email
        'email.required' => 'email should be given',
        'email.email' => 'email should be a valid email address',
        'email.max' => 'email should be less than 255 characters',
        'email.unique' => 'email already taken',

        // password
        'password.required' => 'password should be given',
        'password.string' => 'password should be string',
        'password.min' => 'password should be at least 8 characters',
    ]; 
}

}
