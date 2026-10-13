<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    /* Список статей из БД, сначала самые новые. */
    public function index()
    {
        $articles = Article::latest('date')->get();

        return view('articles.index', ['articles' => $articles]);
    }
}
