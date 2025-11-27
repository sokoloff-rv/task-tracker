<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'required', Rule::in(Task::availableStatuses())],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'assignee_id' => ['sometimes', 'nullable', 'exists:users,id'],
            'attachment' => ['sometimes', 'nullable', 'file', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок задачи обязателен для заполнения!',
            'title.string' => 'Заголовок задачи должен быть строкой!',
            'title.max' => 'Заголовок задачи не может превышать 255 символов!',

            'description.string' => 'Описание задачи должно быть строкой!',

            'status.required' => 'Статус задачи обязателен для заполнения!',
            'status.in' => sprintf(
                'Статус задачи должен быть одним из следующих: %s!',
                implode(', ', Task::availableStatuses())
            ),

            'due_date.date' => 'Дата завершения должна быть корректной!',

            'assignee_id.exists' => 'Указанный исполнитель не найден!',

            'attachment.file' => 'Вложение должно быть файлом!',
            'attachment.max' => 'Размер вложения не может превышать 10 МБ!',
        ];
    }
}
