@extends('layouts.app')

@section('title', 'Регистрация')

@section('content')
    <h1>Регистрация</h1>

    @if ($errors->any())
        <ul class="form-errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('signin') }}" method="POST" class="auth-form">
        @csrf

        <label for="name">Имя</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}">

        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="{{ old('email') }}">

        <label for="password">Пароль</label>
        <input type="password" id="password" name="password">

        <label for="password_confirmation">Повторите пароль</label>
        <input type="password" id="password_confirmation" name="password_confirmation">

        <button type="submit">Зарегистрироваться</button>
    </form>

    <p>Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
@endsection
