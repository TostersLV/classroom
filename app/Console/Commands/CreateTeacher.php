<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateTeacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-teacher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a teacher user account';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::firstOrCreate(
            ['email' => 'teacher@example.com'],
            [
                'first_name' => 'John',
                'last_name' => 'Teacher',
                'password' => Hash::make('password123'),
            ]
        );

        $user->assignRole('teacher');

        $this->info('Teacher user created successfully!');
        $this->info('Email: teacher@example.com');
        $this->info('Password: password123');
    }
}
