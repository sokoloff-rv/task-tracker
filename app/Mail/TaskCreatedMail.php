<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreatedMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(public Task $task)
    {
    }

    public function build(): self
    {
        return $this
            ->subject('Новая задача успешно создана!')
            ->view('emails.task_created');
    }
}
