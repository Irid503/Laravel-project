@extends('layouts.app')

@section('title', 'Статьи')

@section('content')
    <h1>Статьи</h1>

    <a href="{{ route('articles.create') }}" class="btn">+ Добавить статью</a>

    <table class="articles-table">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Заголовок</th>
                <th>Описание</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($articles as $article)
                <tr>
                    <td>{{ $article->date->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('articles.show', $article) }}">{{ $article->name }}</a>
                    </td>
                    <td>{{ $article->shortDesc ?? $article->desc }}</td>
                    <td class="actions">
                        <a href="{{ route('articles.edit', $article) }}">Изменить</a>

                        <form action="{{ route('articles.destroy', $article) }}" method="POST"
                              onsubmit="return confirm('Удалить статью?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="link-button">Удалить</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Статей пока нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- tailwind не подключён, поэтому берём простой шаблон bootstrap-4, стили под него в app.css --}}
    {{ $articles->links('pagination::bootstrap-4') }}
@endsection
