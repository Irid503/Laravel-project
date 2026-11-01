<?php

namespace App\Events;

use App\Models\Article;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/* Событие о новой статье. ShouldBroadcast — событие отправляется в Pusher через очередь,
   поэтому должен работать воркер: php artisan queue:work */
class NewArticleEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Article $article)
    {
        //
    }

    /* Публичный канал test — его слушают все, кто сейчас открыл сайт. */
    public function broadcastOn(): array
    {
        return [
            new Channel('test'),
        ];
    }

    /* Данные, которые придут в браузер (в js это объект article). */
    public function broadcastWith(): array
    {
        return [
            'article' => [
                'id' => $this->article->id,
                'name' => $this->article->name,
            ],
        ];
    }
}
