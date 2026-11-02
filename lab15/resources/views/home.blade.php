@extends('layouts.app')

@section('title', 'Главная')

@section('content')
    <h1>Новости</h1>

    <table class="articles-table">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Превью</th>
                <th>Заголовок</th>
                <th>Описание</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($articles as $index => $article)
                <tr>
                    <td>{{ $article['date'] }}</td>
                    <td>
                        <a href="{{ route('galery', $index) }}">
                            <img src="{{ asset('images/' . $article['preview_image']) }}"
                                 alt="{{ $article['name'] }}"
                                 class="preview-image">
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('galery', $index) }}">{{ $article['name'] }}</a>
                    </td>
                    <td>{{ $article['shortDesc'] ?? $article['desc'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
