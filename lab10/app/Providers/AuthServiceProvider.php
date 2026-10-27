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

        /* Редактировать комментарий может его автор (модератор проходит через before). */
        Gate::define('comment-update', function (User $user, Comment $comment) {
            if ($user->id === $comment->user_id) {
                return Response::allow();
            }

            return Response::deny('Редактировать комментарий может только его автор или модератор.');
        });

        /* Удалять комментарий может его автор (модератор проходит через before). */
        Gate::define('comment-delete', function (User $user, Comment $comment) {
            if ($user->id === $comment->user_id) {
                return Response::allow();
            }

            return Response::deny('Удалять комментарий может только его автор или модератор.');
        });
    }
}
