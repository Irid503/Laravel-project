<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        /* Если статью и автора не передали через for(), фабрика создаст их сама. */
        return [
            'article_id' => Article::factory(),
            'user_id' => User::factory(),
            'text' => fake()->sentences(2, true),
            /* Сгенерированные комментарии сразу одобрены, чтобы были видны под статьёй. */
            'is_approved' => true,
        ];
    }
}
