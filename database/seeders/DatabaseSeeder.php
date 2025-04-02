<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use  App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::truncate();

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@email.com',
            'password' => bcrypt('12345678'),
            'is_admin' => true
        ]);

        User::factory()->create([
            'name' => 'User',
            'email' => 'user@email.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
