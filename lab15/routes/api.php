<?php

use App\Http\Controllers\API\ArticleController;
use App\Http\Controllers\API\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Лаба 15: REST API. Все адреса начинаются с /api, ответы в json.
   Защищённые маршруты требуют заголовок Authorization: Bearer <токен из /api/login>. */

Route::post('/register', [AuthController::class, 'registration'])->name('api.register');
Route::post('/login', [AuthController::class, 'authenticate'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('api.logout');

    /* index, store, show, update, destroy (без create и edit — форм в API нет).
       Имена api.articles.*, чтобы не пересекались с маршрутами сайта articles.*. */
    Route::apiResource('articles', ArticleController::class)
        ->names('api.articles')
        ->middlewareFor('show', 'article.view');
});
