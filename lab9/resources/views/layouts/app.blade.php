<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Сайт')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <nav>
            <a href="{{ route('home') }}">Главная</a>
            @auth
                <a href="{{ route('articles.index') }}">Статьи</a>
            @endauth
            {{-- вкладка видна только модератору (проверка через политику) --}}
            @can('create', App\Models\Article::class)
                <a href="{{ route('articles.create') }}">Создать новость</a>
            @endcan
            @can('comment-moderate')
                <a href="{{ route('comments.moderation') }}">Модерация ({{ App\Models\Comment::where('is_approved', false)->count() }})</a>
            @endcan
            <a href="{{ route('about') }}">О нас</a>
            <a href="{{ route('contacts') }}">Контакты</a>

            <div class="nav-right">
                @guest
                    <a href="{{ route('signin') }}">Регистрация</a>
                    <a href="{{ route('login') }}">Вход</a>
                @endguest

                @auth
                    <span class="nav-user">{{ Auth::user()->name }} ({{ Auth::user()->role?->title ?? 'без роли' }})</span>
                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit">Выйти</button>
                    </form>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @if (session('message'))
            <div class="message">{{ session('message') }}</div>
        @endif

        @yield('content')
    </main>

    <footer>
        Кирилюк Владислав Николаевич группа 251-3210
    </footer>
</body>
</html>