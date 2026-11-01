@extends('layouts.app')

@section('title', $article['name'])

@section('content')
    <a href="{{ route('home') }}">&larr; Назад к новостям</a>

    <h1>{{ $article['name'] }}</h1>
    <p class="article-date">{{ $article['date'] }}</p>

    <img src="{{ asset('images/' . $article['full_image']) }}"
         alt="{{ $article['name'] }}"
         class="full-image">

    <p>{{ $article['desc'] }}</p>
@endsection
