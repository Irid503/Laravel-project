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
        <a href="{{ route('articles.edit', $article) }}" class="btn">Изменить</a>

        <form action="{{ route('articles.destroy', $article) }}" method="POST"
              onsubmit="return confirm('Удалить статью?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>
@endsection
