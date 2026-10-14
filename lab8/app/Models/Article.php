<?php

namespace App\Models;

use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/* Поля такие же, как были в articles.json, чтобы не переделывать шаблоны. */
#[Fillable(['date', 'name', 'shortDesc', 'desc'])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
