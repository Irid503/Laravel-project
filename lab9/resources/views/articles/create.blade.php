@extends('layouts.app')

@section('title', 'Новая статья')

@section('content')
    <a href="{{ route('articles.index') }}">&larr; Назад к статьям</a>

    <h1>Новая статья</h1>

    @if ($errors->any())
        <ul class="form-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('articles.store') }}" method="POST" class="auth-form article-form">
        @csrf

        <label for="date">Дата</label>
        <input type="date" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}">

        <label for="name">Заголовок</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}">

        <label for="shortDesc">Краткое описание</label>
        <input type="text" id="shortDesc" name="shortDesc" value="{{ old('shortDesc') }}">

        <label for="desc">Текст статьи</label>
        <textarea id="desc" name="desc" rows="8">{{ old('desc') }}</textarea>

        <button type="submit">Сохранить</button>
    </form>
@endsection
