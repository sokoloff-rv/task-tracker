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
        $author = User::firstOrCreate(
            ['email' => 'demouser@example.com'],
            [
                'name' => 'Demouser',
                'password' => 'demouser',
            ],
        );

        $assignees = collect([
            User::firstOrCreate(
                ['email' => 'manager@example.com'],
                [
                    'name' => 'Manager',
                    'password' => 'password',
                ],
            ),
            User::firstOrCreate(
                ['email' => 'developer@example.com'],
                [
                    'name' => 'Developer',
                    'password' => 'password',
                ],
            ),
        ]);

        Task::factory()
            ->count(30)
            ->for($author, 'author')
            ->state(fn() => [
                'assignee_id' => $assignees->random()->id,
            ])
            ->create();
    }
}
