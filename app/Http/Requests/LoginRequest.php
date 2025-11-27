<?php

namespace App\Http\Requests;

class LoginRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Email обязателен для заполнения!',
            'email.string' => 'Email должен быть строкой!',
            'email.email' => 'Укажите корректный email!',

            'password.required' => 'Пароль обязателен для заполнения!',
            'password.string' => 'Пароль должен быть строкой!',
        ];
    }
}
