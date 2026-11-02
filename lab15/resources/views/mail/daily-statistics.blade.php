<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Статистика сайта</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; color: #222; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden;">
        <div style="background: #1a1a2e; color: #fff; padding: 15px 25px; font-weight: bold;">
            {{ config('app.name') }}
        </div>

        <div style="padding: 25px;">
            <h2 style="margin-top: 0;">Статистика за {{ today()->format('d.m.Y') }}</h2>

            <p>Просмотров статей: <b>{{ $viewsCount }}</b></p>
            <p>Новых комментариев: <b>{{ $commentsCount }}</b></p>

            @if ($topArticles->isNotEmpty())
                <h3>Самые просматриваемые статьи</h3>

                <table style="width: 100%; border-collapse: collapse;">
                    @foreach ($topArticles as $row)
                        <tr>
                            <td style="padding: 6px 0; border-bottom: 1px solid #eee;">{{ $row->article?->name ?? 'Статья удалена' }}</td>
                            <td style="padding: 6px 0; border-bottom: 1px solid #eee; text-align: right;">{{ $row->views }}</td>
                        </tr>
                    @endforeach
                </table>
            @endif
        </div>
    </div>
</body>
</html>
