<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Validation\Rule;

class TaskIndexRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'status' => ['sometimes', Rule::in(Task::availableStatuses())],
            'assignee_id' => ['sometimes', 'integer', 'exists:users,id'],
            'due_date' => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => sprintf(
                'Статус задачи должен быть одним из следующих: %s!',
                implode(', ', Task::availableStatuses())
            ),

            'assignee_id.integer' => 'Идентификатор исполнителя должен быть числом!',
            'assignee_id.exists' => 'Указанный исполнитель не найден!',

            'due_date.date' => 'Дата завершения должна быть корректной!',
        ];
    }
}
