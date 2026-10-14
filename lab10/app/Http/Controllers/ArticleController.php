<?php

namespace App\Http\Controllers;

use App\Jobs\VeryLongJob;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ArticleController extends Controller
{
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

    /* Список статей с пагинацией по 5 штук на странице. */
    public function index()
    {
        Gate::authorize('viewAny', Article::class);

        $articles = Article::latest('date')->paginate(5);

        return view('articles.index', ['articles' => $articles]);
    }

    /* Форма создания новой статьи. */
    public function create()
    {
        Gate::authorize('create', Article::class);

        return view('articles.create');
    }

    /* Сохраняет новую статью в БД. */
    public function store(Request $request)
    {
        Gate::authorize('create', Article::class);

        $data = $this->validateArticle($request);

        $article = Article::create($data);

        /* Письмо модераторам ставим в очередь, чтобы страница не ждала отправку. */
        VeryLongJob::dispatch($article);

        return redirect()->route('articles.show', $article)->with('message', 'Статья добавлена');
    }

    /* Страница одной статьи вместе с комментариями. */
    public function show(Article $article)
    {
        Gate::authorize('view', $article);

        /* На странице статьи только комментарии, прошедшие модерацию. */
        $comments = $article->comments()->where('is_approved', true)->with('user')->latest()->get();

        return view('articles.show', ['article' => $article, 'comments' => $comments]);
    }

    /* Форма редактирования статьи. */
    public function edit(Article $article)
    {
        Gate::authorize('update', $article);

        return view('articles.edit', ['article' => $article]);
    }

    /* Сохраняет изменения статьи. */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', $article);

        $data = $this->validateArticle($request);

        $article->update($data);

        return redirect()->route('articles.show', $article)->with('message', 'Статья обновлена');
    }

    /* Удаляет статью. */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index')->with('message', 'Статья удалена');
    }
}
