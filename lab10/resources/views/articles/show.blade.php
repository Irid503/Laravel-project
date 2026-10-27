@extends('layouts.app')

@section('title', $article->name)

@section('content')
    <a href="{{ route('articles.index') }}">&larr; Назад к статьям</a>

    <h1>{{ $article->name }}</h1>
    <p class="article-date">{{ $article->date->format('d.m.Y') }}</p>

    @if ($article->shortDesc)
        <p><b>{{ $article->shortDesc }}</b></p>
    @endif

    <p>{{ $article->desc }}</p>

    <div class="actions">
        @can('update', $article)
            <a href="{{ route('articles.edit', $article) }}" class="btn">Изменить</a>
        @endcan

        @can('delete', $article)
            <form action="{{ route('articles.destroy', $article) }}" method="POST"
                  onsubmit="return confirm('Удалить статью?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Удалить</button>
            </form>
        @endcan
    </div>

    <h2 class="comments-title">Комментарии ({{ $comments->count() }})</h2>

    @can('comment-create')
        @if ($errors->any())
            <ul class="form-errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('comments.store', $article) }}" method="POST" class="auth-form article-form">
            @csrf
            <textarea name="text" rows="3" placeholder="Напишите комментарий...">{{ old('text') }}</textarea>
            <button type="submit">Отправить</button>
        </form>
    @endcan

    @forelse ($comments as $comment)
        <div class="comment">
            <div class="comment-head">
                <b>{{ $comment->user->name }}</b>
                <span class="article-date">{{ $comment->created_at->format('d.m.Y H:i') }}</span>
            </div>

            <p>{{ $comment->text }}</p>

            {{-- кнопки видны только автору комментария и модератору --}}
            <div class="actions comment-actions">
                @can('comment-update', $comment)
                    <a href="{{ route('comments.edit', $comment) }}">Изменить</a>
                @endcan

                @can('comment-delete', $comment)
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST"
                          onsubmit="return confirm('Удалить комментарий?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="link-button">Удалить</button>
                    </form>
                @endcan
            </div>
        </div>
    @empty
        <p>Комментариев пока нет.</p>
    @endforelse
@endsection
