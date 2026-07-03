<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'owner@sistemkasi.test'],
            [
                'name' => 'Owner',
                'username' => 'owner',
                'password' => 'password',
                'role' => UserRole::Owner,
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@sistemkasi.test'],
            [
                'name' => 'Admin Kasir',
                'username' => 'admin',
                'password' => 'password',
                'role' => UserRole::Admin,
            ],
        );
    }
}
