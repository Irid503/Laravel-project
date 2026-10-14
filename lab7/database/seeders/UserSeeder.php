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

        /* Модератор для входа: moderator@mail.ru / 12345678 */
        User::create([
            'name' => 'Модератор',
            'email' => 'moderator@mail.ru',
            'password' => '12345678',
            'role_id' => $moderator->id,
        ]);
    }
}
