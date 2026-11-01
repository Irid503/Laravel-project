<?php

namespace App\Console\Commands;

use App\Mail\DailyStatisticsMail;
use App\Models\ArticleView;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

#[Signature('stats:send')]
#[Description('Отправить модераторам статистику сайта за сегодня')]
class SendDailyStatistics extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        /* Количество просмотров статей за сегодня (из таблицы с сохранёнными url). */
        $viewsCount = ArticleView::whereDate('created_at', today())->count();

        /* Самые просматриваемые статьи за сегодня: статья и число просмотров. */
        $topArticles = ArticleView::whereDate('created_at', today())
            ->selectRaw('article_id, count(*) as views')
            ->groupBy('article_id')
            ->orderByDesc('views')
            ->with('article')
            ->limit(5)
            ->get();

        /* Количество комментариев, добавленных сегодня. */
        $commentsCount = Comment::whereDate('created_at', today())->count();

        $moderators = User::whereHas('role', function ($query) {
            $query->where('name', 'moderator');
        })->get();

        Mail::to($moderators)->send(new DailyStatisticsMail($viewsCount, $topArticles, $commentsCount));

        $this->info("Статистика отправлена: просмотров {$viewsCount}, новых комментариев {$commentsCount}.");
    }
}
