@extends('layouts.app')

@section('title', 'Модерация комментариев')

@section('content')
    <h1>Модерация комментариев</h1>

    <table class="articles-table">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Автор</th>
                <th>Статья</th>
                <th>Комментарий</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($comments as $comment)
                <tr>
                    <td>{{ $comment->created_at->format('d.m.Y H:i') }}</td>
                    <td>{{ $comment->user->name }}</td>
                    <td>
                        <a href="{{ route('articles.show', $comment->article) }}">{{ $comment->article->name }}</a>
                    </td>
                    <td>{{ $comment->text }}</td>
                    <td class="actions">
                        <form action="{{ route('comments.accept', $comment) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn">Принять</button>
                        </form>

                        <form action="{{ route('comments.reject', $comment) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Отклонить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Новых комментариев нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
