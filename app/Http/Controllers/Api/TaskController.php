<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreRequest;
use App\Mail\TaskCreatedMail;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function store(TaskStoreRequest $request)
    {
        $data = $request->validated();

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? Task::STATUS_PLANNED,
            'due_date' => $data['due_date'] ?? null,
            'assignee_id' => $data['assignee_id'] ?? null,
        ]);

        $attachmentUrl = null;

        if ($request->hasFile('attachment')) {
            $media = $task
                ->addMediaFromRequest('attachment')
                ->toMediaCollection('attachments');

            $attachmentUrl = $media->getUrl();
        }

        $recipient = $task->assignee ?? $request->user();

        if ($recipient) {
            Mail::to($recipient)->send(new TaskCreatedMail($task));
        }

        return response()->json([
            'message' => 'Задача успешно создана!',
            'task' => $task->load('assignee'),
            'attachment_url' => $attachmentUrl,
        ], 201);
    }
}
