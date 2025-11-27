<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskIndexRequest;
use App\Http\Requests\TaskStoreRequest;
use App\Http\Requests\TaskUpdateRequest;
use App\Mail\TaskCreatedMail;
use App\Models\Task;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index(TaskIndexRequest $request)
    {
        $filters = $request->validated();

        $tasks = Task::with(['assignee', 'author'])
            ->when(isset($filters['status']), fn($query) => $query->where('status', $filters['status']))
            ->when(isset($filters['assignee_id']), fn($query) => $query->where('assignee_id', $filters['assignee_id']))
            ->when(isset($filters['due_date']), fn($query) => $query->whereDate('due_date', $filters['due_date']))
            ->get();

        $tasks->transform(function (Task $task) {
            $task->setAttribute('attachment_url', $this->getAttachmentUrl($task));

            return $task;
        });

        return response()->json([
            'message' => "Список из {$tasks->count()} задач успешно получен!",
            'tasks' => $tasks,
        ]);
    }

    public function store(TaskStoreRequest $request)
    {
        $data = $request->validated();

        $task = Task::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? Task::STATUS_PLANNED,
            'due_date' => $data['due_date'] ?? null,
            'author_id' => $request->user()->id,
            'assignee_id' => $data['assignee_id'] ?? null,
        ]);

        $attachmentUrl = null;

        $attachmentUrl = $this->syncAttachment($request, $task);

        $recipient = $task->assignee ?? $request->user();

        if ($recipient) {
            Mail::to($recipient)->send(new TaskCreatedMail($task));
        }

        return response()->json([
            'message' => 'Задача успешно создана!',
            'task' => $task->load(['assignee', 'author']),
            'attachment_url' => $attachmentUrl,
        ], 201);
    }

    public function show(Task $task)
    {
        return response()->json([
            'message' => 'Задача успешно получена!',
            'task' => $task->load(['assignee', 'author']),
            'attachment_url' => $this->getAttachmentUrl($task),
        ]);
    }

    public function update(TaskUpdateRequest $request, Task $task)
    {
        $data = $request->validated();

        $task->update([
            'title' => $data['title'] ?? $task->title,
            'description' => $data['description'] ?? $task->description,
            'status' => $data['status'] ?? $task->status,
            'due_date' => $data['due_date'] ?? $task->due_date,
            'assignee_id' => $data['assignee_id'] ?? $task->assignee_id,
        ]);

        $attachmentUrl = $this->syncAttachment($request, $task);

        return response()->json([
            'message' => 'Задача успешно обновлена!',
            'task' => $task->load(['assignee', 'author']),
            'attachment_url' => $attachmentUrl,
        ]);
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Задача успешно удалена!',
        ]);
    }

    private function syncAttachment($request, Task $task): ?string
    {
        if ($request->hasFile('attachment')) {
            $media = $task
                ->addMediaFromRequest('attachment')
                ->toMediaCollection('attachment');

            return $media->getUrl();
        }

        return $this->getAttachmentUrl($task);
    }

    private function getAttachmentUrl(Task $task): ?string
    {
        $media = $task->getFirstMedia('attachment');

        return $media?->getUrl();
    }
}
