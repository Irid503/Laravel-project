<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

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
        $articles = Article::latest('date')->paginate(5);

        return view('articles.index', ['articles' => $articles]);
    }

    /* Форма создания новой статьи. */
    public function create()
    {
        return view('articles.create');
    }

    /* Сохраняет новую статью в БД. */
    public function store(Request $request)
    {
        $data = $this->validateArticle($request);

        $article = Article::create($data);

        return redirect()->route('articles.show', $article)->with('message', 'Статья добавлена');
    }

    /* Страница одной статьи. */
    public function show(Article $article)
    {
        return view('articles.show', ['article' => $article]);
    }

    /* Форма редактирования статьи. */
    public function edit(Article $article)
    {
        return view('articles.edit', ['article' => $article]);
    }

    /* Сохраняет изменения статьи. */
    public function update(Request $request, Article $article)
    {
        $data = $this->validateArticle($request);

        $article->update($data);

        return redirect()->route('articles.show', $article)->with('message', 'Статья обновлена');
    }

    /* Удаляет статью. */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()->route('articles.index')->with('message', 'Статья удалена');
    }
}
