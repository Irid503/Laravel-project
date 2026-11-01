@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <h1>Вход</h1>

    @if ($errors->any())
        <ul class="form-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('authenticate') }}" method="POST" class="auth-form">
        @csrf

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}">

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password">

        <label class="checkbox">
            <input type="checkbox" name="remember"> Запомнить меня
        </label>

        <button type="submit">Войти</button>
    </form>

    <p>Нет аккаунта? <a href="{{ route('signin') }}">Зарегистрироваться</a></p>
@endsection
