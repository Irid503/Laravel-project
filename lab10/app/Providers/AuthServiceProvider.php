<?php

namespace App\Providers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use App\Policies\ArticlePolicy;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        /* Хук before проверяется раньше всех шлюзов и политик: модератору можно всё.
           Если вернуть null, проверка идёт дальше по обычным правилам. */
        Gate::before(function (User $user, string $ability) {
            if ($user->isModerator()) {
                return true;
            }

            return null;
        });

        Gate::policy(Article::class, ArticlePolicy::class);

        /* Комментировать может любой пользователь с ролью (читатель). */
        Gate::define('comment-create', function (User $user) {
            if ($user->role === null) {
                return Response::deny('У вас нет роли, комментарии недоступны.');
            }

            return Response::allow();
        });

        /* Модерация комментариев — тоже только модератор. */
        Gate::define('comment-moderate', function (User $user) {
            return Response::deny('Модерировать комментарии может только модератор.');
        });

        /* Удалять комментарии может только модератор (он проходит через before). */
        Gate::define('comment-delete', function (User $user, Comment $comment) {
            return Response::deny('Удалять комментарии может только модератор.');
        });
    }
}
