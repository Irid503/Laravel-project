<?php

namespace App\Http\Controllers\API;

use App\Events\NewArticleEvent;
use App\Http\Controllers\Controller;
use App\Jobs\VeryLongJob;
use App\Models\Article;
use App\Models\User;
use App\Notifications\NewArticleNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

/* Лаба 15: копия ArticleController для REST API — вместо view и redirect возвращает json. */
class ArticleController extends Controller
{
    /* Сколько статей на одной странице списка. */
    private const PER_PAGE = 5;

    /* Валидация одна и для создания, и для редактирования, поэтому вынесена отдельно. */
    private function validateArticle(Request $request): array
    {
        return $request->validate([
            'date' => 'required|date',
            'name' => 'required|string|min:3|max:255',
            'shortDesc' => 'nullable|string|max:255',
            'desc' => 'required|string|min:10',
        ], [
            'required' => 'Поле «:attribute» обязательно для заполнения.',
            'date' => 'Поле «:attribute» должно быть датой.',
            'min' => 'Поле «:attribute» должно быть не короче :min символов.',
            'max' => 'Поле «:attribute» должно быть не длиннее :max символов.',
        ], [
            'date' => 'Дата',
            'name' => 'Заголовок',
            'shortDesc' => 'Краткое описание',
            'desc' => 'Текст статьи',
        ]);
    }

    /* GET /api/articles — список статей с пагинацией. Каждая страница кэшируется на 10 минут. */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Article::class);

        $page = (int) $request->query('page', 1);

        $articles = Cache::remember('articles_page_' . $page, 600, function () {
            return Article::latest('date')->paginate(self::PER_PAGE);
        });

        /* Пагинатор в json: data — статьи, плюс current_page, last_page, total и ссылки. */
        return response()->json($articles);
    }

    /* POST /api/articles — сохраняет новую статью в БД. */
    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);

        $data = $this->validateArticle($request);

        $article = Article::create($data);

        /* Новая статья меняет все страницы списка, поэтому удаляем их кэш (включая пагинацию). */
        $pages = (int) ceil(Article::count() / self::PER_PAGE);

        for ($page = 1; $page <= $pages; $page++) {
            Cache::forget('articles_page_' . $page);
        }

        /* Письмо модераторам ставим в очередь, чтобы страница не ждала отправку. */
        VeryLongJob::dispatch($article);

        /* Push-уведомление всем, кто сейчас на сайте (через Pusher). */
        NewArticleEvent::dispatch($article);

        /* Уведомление в БД всем читателям, кроме того, кто сейчас вошёл и создал статью. */
        $readers = User::whereHas('role', function ($query) {
            $query->where('name', 'reader');
        })->where('id', '!=', $request->user()->id)->get();

        Notification::send($readers, new NewArticleNotification($article));

        /* 201 Created — ресурс создан. */
        return response()->json([
            'message' => 'Статья добавлена',
            'article' => $article,
        ], 201);
    }

    /* GET /api/articles/{id} — одна статья вместе с комментариями. */
    public function show(Article $article)
    {
        Gate::authorize('view', $article);

        /* На странице статьи только комментарии, прошедшие модерацию. Кэшируем без срока,
           кэш сбрасывается при изменении статьи или комментариев. */
        $comments = Cache::rememberForever('article_comments_' . $article->id, function () use ($article) {
            return $article->comments()->where('is_approved', true)->with('user')->latest()->get();
        });

        return response()->json([
            'article' => $article,
            'comments' => $comments,
        ]);
    }

    /* PUT /api/articles/{id} — сохраняет изменения статьи. */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);

        $data = $this->validateArticle($request);

        $article->update($data);

        /* Статья изменилась — очищаем весь кэш. */
        Cache::flush();

        return response()->json([
            'message' => 'Статья обновлена',
            'article' => $article,
        ]);
    }

    /* DELETE /api/articles/{id} — удаляет статью. */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        /* Статья (и её комментарии) удалена — очищаем весь кэш. */
        Cache::flush();

        return response()->json([
            'message' => 'Статья удалена',
        ]);
    }
}
