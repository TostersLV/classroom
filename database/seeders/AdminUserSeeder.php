<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'rpeizums@gmail.com'], // Find by email
            [
                'first_name' => 'Rainers',
                'last_name' => 'Peizums',
                'password' => Hash::make('Pixelboy1#'), // Use a strong password in production!
            ]
        );

        // Assign the 'admin' role
        $admin->assignRole('admin'); 
    }
}
