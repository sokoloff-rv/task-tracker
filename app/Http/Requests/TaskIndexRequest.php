<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['sometimes', 'in:planned,in_progress,done'],
            'assignee_id' => ['sometimes', 'integer', 'exists:users,id'],
            'due_date' => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Статус задачи должен быть одним из следующих: planned, in_progress, done!',

            'assignee_id.integer' => 'Идентификатор исполнителя должен быть числом!',
            'assignee_id.exists' => 'Указанный исполнитель не найден!',

            'due_date.date' => 'Дата завершения должна быть корректной!',
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
