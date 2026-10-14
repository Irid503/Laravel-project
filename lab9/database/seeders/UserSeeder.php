<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $moderator = Role::where('name', 'moderator')->first();

        /* Модератор для входа: MODERATOR_EMAIL из .env / 12345678
           (на эту почту приходят письма о новых статьях) */
        User::create([
            'name' => 'Модератор',
            'email' => env('MODERATOR_EMAIL', 'moderator@example.com'),
            'password' => '12345678',
            'role_id' => $moderator->id,
        ]);
    }
}
