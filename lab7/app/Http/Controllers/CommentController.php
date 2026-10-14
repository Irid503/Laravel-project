<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /* Добавляет комментарий к статье. */
    public function store(Request $request, Article $article)
    {
        Gate::authorize('comment-create');

        $data = $request->validate([
            'text' => 'required|string|min:2|max:1000',
        ], [
            'text.required' => 'Комментарий не может быть пустым.',
            'text.min' => 'Комментарий должен быть не короче :min символов.',
            'text.max' => 'Комментарий должен быть не длиннее :max символов.',
        ]);

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'text' => $data['text'],
        ]);

        return redirect()->route('articles.show', $article)->with('message', 'Комментарий добавлен');
    }

    /* Удаляет комментарий (только модератор). */
    public function destroy(Comment $comment)
    {
        Gate::authorize('comment-delete', $comment);

        $article = $comment->article;
        $comment->delete();

        return redirect()->route('articles.show', $article)->with('message', 'Комментарий удалён');
    }
}
