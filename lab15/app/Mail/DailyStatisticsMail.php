<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class DailyStatisticsMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public int $viewsCount,
        public Collection $topArticles,
        public int $commentsCount,
    ) {
        //
    }

    /* Как и в NewArticleMail — метод build(). Публичные свойства доступны в шаблоне. */
    public function build()
    {
        return $this->subject('Статистика сайта за ' . today()->format('d.m.Y'))
            ->view('mail.daily-statistics');
    }
}
