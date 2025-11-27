<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::defaults()],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя пользователя обязательно для заполнения!',
            'name.string' => 'Имя пользователя должно быть строкой!',
            'name.max' => 'Имя пользователя не может превышать 255 символов!',

            'email.required' => 'Email обязателен для заполнения!',
            'email.string' => 'Email должен быть строкой!',
            'email.email' => 'Укажите корректный email!',
            'email.max' => 'Email не может превышать 255 символов!',
            'email.unique' => 'Пользователь с таким email уже зарегистрирован!',

            'password.required' => 'Пароль обязателен для заполнения!',
            'password.string' => 'Пароль должен быть строкой!',

            'password.min' => 'Пароль должен содержать минимум :min символов!',
            'password.letters' => 'Пароль должен содержать хотя бы одну букву!',
            'password.mixed' => 'Пароль должен содержать строчные и заглавные буквы!',
            'password.numbers' => 'Пароль должен содержать хотя бы одну цифру!',
            'password.symbols' => 'Пароль должен содержать хотя бы один специальный символ!',
            'password.uncompromised' => 'Этот пароль небезопасен — он был найден в утечках данных. Выберите другой!',
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $response = response()->json([
            'message' => 'Ошибка валидации данных!',
            'errors'  => $validator->errors(),
        ], 422);

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
