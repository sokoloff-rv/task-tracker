<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->string('title', 255);
            $table->text('description')->nullable();

            $table->enum('status', ['planned', 'in_progress', 'done'])
                ->default('planned');

            $table->date('due_date')->nullable();

            $table->foreignId('assignee_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('status');
            $table->index('assignee_id');
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
