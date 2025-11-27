<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'demouser@example.com'],
            [
                'name' => 'demouser',
                'password' => 'demouser',
            ],
        );

        Task::factory()
            ->count(10)
            ->for($user, 'assignee')
            ->create();
    }
}
