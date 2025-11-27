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
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:planned,in_progress,done'],
            'due_date' => ['nullable', 'date'],
            'assignee_id' => ['nullable', 'exists:users,id'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];
    }
}
