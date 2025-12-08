<?php
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$user = User::firstOrCreate(
    ['email' => 'teacher@example.com'],
    [
        'first_name' => 'John',
        'last_name' => 'Teacher',
        'password' => Hash::make('password123'),
    ]
);

$user->assignRole('teacher');

echo "Teacher user created!\n";
echo "Email: teacher@example.com\n";
echo "Password: password123\n";
