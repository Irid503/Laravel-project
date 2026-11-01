@extends('layouts.app')

@section('title', 'Редактирование')

@section('content')
    <a href="{{ route('articles.show', $article) }}">&larr; Назад к статье</a>

    <h1>Редактирование статьи</h1>

    @if ($errors->any())
        <ul class="form-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('articles.update', $article) }}" method="POST" class="auth-form article-form">
        @csrf
        @method('PUT')

        <label for="date">Дата</label>
        <input type="date" id="date" name="date" value="{{ old('date', $article->date->format('Y-m-d')) }}">

        <label for="name">Заголовок</label>
        <input type="text" id="name" name="name" value="{{ old('name', $article->name) }}">

        <label for="shortDesc">Краткое описание</label>
        <input type="text" id="shortDesc" name="shortDesc" value="{{ old('shortDesc', $article->shortDesc) }}">

        <label for="desc">Текст статьи</label>
        <textarea id="desc" name="desc" rows="8">{{ old('desc', $article->desc) }}</textarea>

        <button type="submit">Сохранить изменения</button>
    </form>
@endsection
