@extends('layouts.app')

@section('title', 'Редактирование комментария')

@section('content')
    <a href="{{ route('articles.show', $comment->article) }}">&larr; Назад к статье</a>

    <h1>Редактирование комментария</h1>

    <p>К статье: <b>{{ $comment->article->name }}</b></p>

    @if ($errors->any())
        <ul class="form-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('comments.update', $comment) }}" method="POST" class="auth-form article-form">
        @csrf
        @method('PUT')

        <textarea name="text" rows="4">{{ old('text', $comment->text) }}</textarea>

        <button type="submit">Сохранить изменения</button>
    </form>
@endsection
