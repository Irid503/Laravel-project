<?php

namespace App\Notifications;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewArticleNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Article $article)
    {
        //
    }

    /* Канал database — уведомление сохраняется в таблицу notifications. */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /* Данные, которые попадут в поле data таблицы notifications (как json). */
    public function toDatabase(object $notifiable): array
    {
        return [
            'article_id' => $this->article->id,
            'article_name' => $this->article->name,
        ];
    }
}
