<?php

namespace App\Http\Controllers;

class MainController extends Controller
{
    /* Читает данные из json-файла и возвращает их как массив. */
    private function getArticles(): array
    {
        $path = public_path('data/articles.json');
        $json = file_get_contents($path);

        return json_decode($json, true) ?? [];
    }

    /* Главная страница — список новостей из json. */
    public function index()
    {
        $articles = $this->getArticles();

        return view('home', ['articles' => $articles]);
    }

    /* Страница galery — полное изображение конкретной новости. */
    public function galery(int $id)
    {
        $articles = $this->getArticles();

        if (!isset($articles[$id])) {
            abort(404);
        }

        return view('galery', ['article' => $articles[$id]]);
    }
}
