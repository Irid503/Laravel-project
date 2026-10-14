@extends('layouts.app')

@section('title', 'Контакты')

@section('content')
    <h1>Контакты</h1>
    <ul>
        @foreach ($contacts as $key => $value)
            <li>{{ $key }}:{{ $value }}</li>
        @endforeach
    </ul>
@endsection