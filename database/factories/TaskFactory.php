<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement([
                Task::STATUS_PLANNED,
                Task::STATUS_IN_PROGRESS,
                Task::STATUS_DONE,
            ]),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+1 month'),
            'author_id' => User::factory(),
            'assignee_id' => null,
        ];
    }
}
