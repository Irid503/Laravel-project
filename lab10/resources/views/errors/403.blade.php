@extends('layouts.app')

@section('title', 'Доступ запрещён')

@section('content')
    <h1>403 — доступ запрещён</h1>

    {{-- сюда попадает текст из Response::deny() в политике или шлюзе --}}
    <p>{{ $exception->getMessage() ?: 'У вас нет прав для этого действия.' }}</p>

    <a href="{{ url()->previous() }}">&larr; Вернуться назад</a>
@endsection
