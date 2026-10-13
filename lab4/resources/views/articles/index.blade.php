@extends('layouts.app')

@section('title', 'Статьи')

@section('content')
    <h1>Статьи</h1>

    <table class="articles-table">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Заголовок</th>
                <th>Описание</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($articles as $article)
                <tr>
                    <td>{{ $article->date->format('d.m.Y') }}</td>
                    <td>{{ $article->name }}</td>
                    <td>{{ $article->shortDesc ?? $article->desc }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">Статей пока нет.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
