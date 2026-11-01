<?php

namespace App\Mail;

use App\Models\Article;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewArticleMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public Article $article)
    {
        //
    }

    /* Вместо envelope() и content() из заготовки — метод build(), как в задании.
       Публичное свойство $article автоматически доступно в шаблоне. */
    public function build()
    {
        return $this->subject('Новая статья: ' . $this->article->name)
            ->view('mail.new-article');
    }
}
