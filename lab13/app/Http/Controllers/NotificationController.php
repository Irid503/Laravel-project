<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /* Клик по уведомлению: отмечаем его прочитанным и открываем статью. */
    public function read(Request $request, string $id)
    {
        /* Ищем только среди уведомлений текущего пользователя, чужое открыть нельзя. */
        $notification = $request->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        $article = Article::find($notification->data['article_id']);

        if ($article === null) {
            return redirect()->route('articles.index')->with('message', 'Эта статья уже удалена');
        }

        return redirect()->route('articles.show', $article);
    }
}
