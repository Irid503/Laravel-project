<?php

namespace App\Http\Middleware;

use App\Models\ArticleView;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/* Посредник сохраняет каждый просмотр статьи в таблицу article_views. */
class LogArticleView
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        /* Записываем только успешно открытую статью (без 403/404). */
        if ($response->isSuccessful()) {
            ArticleView::create([
                'article_id' => $request->route('article')->id,
                'url' => $request->fullUrl(),
            ]);
        }

        return $response;
    }
}
