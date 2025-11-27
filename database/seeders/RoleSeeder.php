<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if roles exist before creating them to avoid duplicates
        Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web' // Standard Laravel web guard
        ]);

        Role::firstOrCreate([
            'name' => 'teacher',
            'guard_name' => 'web'
        ]);

        Role::firstOrCreate([
            'name' => 'user',
            'guard_name' => 'web'
        ]);
        
        
    }
}
