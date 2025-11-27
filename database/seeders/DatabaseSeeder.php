<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $usersData = [
            ['email' => 'demouser@example.com',  'name' => 'Demouser',  'password' => 'demouser'],
            ['email' => 'manager@example.com',   'name' => 'Manager',   'password' => 'password'],
            ['email' => 'developer@example.com', 'name' => 'Developer', 'password' => 'password'],
        ];

        $users = collect($usersData)
            ->map(fn($data) => User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt($data['password']),
                ]
            ));

        $author = $users->first();

        Task::factory()
            ->count(30)
            ->for($author, 'author')
            ->state(fn() => [
                'assignee_id' => $users->random()->id,
            ])
            ->create();
    }
}
