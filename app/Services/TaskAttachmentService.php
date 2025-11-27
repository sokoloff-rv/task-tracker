<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskAttachmentService
{
    public function syncAttachment(Request $request, Task $task): ?string
    {
        if ($request->hasFile('attachment')) {
            $media = $task
                ->addMediaFromRequest('attachment')
                ->toMediaCollection('attachment');

            return $media->getUrl();
        }

        return $this->getAttachmentUrl($task);
    }

    public function getAttachmentUrl(Task $task): ?string
    {
        return $task->getFirstMedia('attachment')?->getUrl();
    }

    public function appendAttachmentUrl(Task $task): Task
    {
        $task->setAttribute('attachment_url', $this->getAttachmentUrl($task));

        return $task;
    }
}
