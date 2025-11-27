<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['required', 'in:planned,in_progress,done'],
            'due_date' => ['nullable', 'date'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок задачи обязателен для заполнения!',
            'title.string' => 'Заголовок задачи должен быть строкой!',
            'title.max' => 'Заголовок задачи не может превышать 255 символов!',

            'description.required' => 'Описание задачи обязательно для заполнения!',
            'description.string' => 'Описание задачи должно быть строкой!',

            'status.required' => 'Статус задачи обязателен для заполнения!',
            'status.in' => 'Статус задачи должен быть одним из следующих: planned, in_progress, done!',

            'due_date.date' => 'Дата завершения должна быть корректной!',

            'attachment.file' => 'Вложение должно быть файлом!',
            'attachment.max' => 'Размер вложения не может превышать 10 МБ!',
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
