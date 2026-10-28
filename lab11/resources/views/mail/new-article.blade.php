<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Новая статья</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; color: #222; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden;">
        <div style="background: #1a1a2e; color: #fff; padding: 15px 25px; font-weight: bold;">
            {{ config('app.name') }}
        </div>

        <div style="padding: 25px;">
            <h2 style="margin-top: 0;">На сайте добавлена новая статья</h2>

            <p style="color: #666; font-size: 14px;">{{ $article->date->format('d.m.Y') }}</p>

            <h3>{{ $article->name }}</h3>

            @if ($article->shortDesc)
                <p><b>{{ $article->shortDesc }}</b></p>
            @endif

            <p>{{ \Illuminate\Support\Str::limit($article->desc, 300) }}</p>

            <a href="{{ route('articles.show', $article) }}"
               style="display: inline-block; padding: 10px 16px; background: #1a1a2e; color: #fff; text-decoration: none; border-radius: 4px;">
                Открыть статью
            </a>
        </div>
    </div>
</body>
</html>
