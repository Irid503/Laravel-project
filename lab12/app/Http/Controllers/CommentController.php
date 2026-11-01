<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    /* Валидация одна и для добавления, и для редактирования комментария. */
    private function validateComment(Request $request): array
    {
        return $request->validate([
            'text' => 'required|string|min:2|max:1000',
        ], [
            'text.required' => 'Комментарий не может быть пустым.',
            'text.min' => 'Комментарий должен быть не короче :min символов.',
            'text.max' => 'Комментарий должен быть не длиннее :max символов.',
        ]);
    }

    /* Добавляет комментарий к статье. Комментарий модератора сразу одобрен, остальные ждут проверки. */
    public function store(Request $request, Article $article)
    {
        Gate::authorize('comment-create');

        $data = $this->validateComment($request);

        $isApproved = $request->user()->isModerator();

        $article->comments()->create([
            'user_id' => $request->user()->id,
            'text' => $data['text'],
            'is_approved' => $isApproved,
        ]);

        $message = $isApproved ? 'Комментарий добавлен' : 'Комментарий отправлен и ожидает модерации';

        return redirect()->route('articles.show', $article)->with('message', $message);
    }

    /* Форма редактирования комментария (автор или модератор). */
    public function edit(Comment $comment)
    {
        Gate::authorize('comment-update', $comment);

        return view('comments.edit', ['comment' => $comment]);
    }

    /* Сохраняет изменения. Если правил не модератор, комментарий снова уходит на модерацию. */
    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('comment-update', $comment);

        $data = $this->validateComment($request);

        $isApproved = $request->user()->isModerator() ? $comment->is_approved : false;

        $comment->update([
            'text' => $data['text'],
            'is_approved' => $isApproved,
        ]);

        $message = $isApproved ? 'Комментарий обновлён' : 'Комментарий обновлён и снова ожидает модерации';

        return redirect()->route('articles.show', $comment->article)->with('message', $message);
    }

    /* Удаляет комментарий (автор или модератор). */
    public function destroy(Comment $comment)
    {
        Gate::authorize('comment-delete', $comment);

        $article = $comment->article;
        $comment->delete();

        return redirect()->route('articles.show', $article)->with('message', 'Комментарий удалён');
    }

    /* Страница модерации — только комментарии, которые ещё не проверены. */
    public function moderation()
    {
        Gate::authorize('comment-moderate');

        $comments = Comment::where('is_approved', false)->with(['user', 'article'])->oldest()->get();

        return view('comments.moderation', ['comments' => $comments]);
    }

    /* Принять комментарий — он появится под статьёй. */
    public function accept(Comment $comment)
    {
        Gate::authorize('comment-moderate');

        $comment->update(['is_approved' => true]);

        return redirect()->route('comments.moderation')->with('message', 'Комментарий принят');
    }

    /* Отклонить комментарий — просто удаляем его. */
    public function reject(Comment $comment)
    {
        Gate::authorize('comment-moderate');

        $comment->delete();

        return redirect()->route('comments.moderation')->with('message', 'Комментарий отклонён');
    }
}
