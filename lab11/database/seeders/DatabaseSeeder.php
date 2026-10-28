<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Comment;
use App\Models\Role;
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
        /* Сначала роли, потом модератор — ему нужен role_id. */
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        /* Обычный читатель для проверки: reader@mail.ru / password */
        $reader = User::factory()->create([
            'name' => 'Читатель',
            'email' => 'reader@mail.ru',
            'role_id' => Role::where('name', 'reader')->value('id'),
        ]);

        /* Заполняем таблицу статей фейковыми данными через фабрику
           и к каждой статье добавляем по 2 одобренных комментария от читателя. */
        Article::factory(15)->create()->each(function (Article $article) use ($reader) {
            Comment::factory(2)->for($article)->for($reader)->create();
        });
    }
}
