<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MainController::class, 'index'])->name('home');

Route::get('/galery/{id}', [MainController::class, 'galery'])->name('galery');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    $contacts = [
        'Телефон' => '+7 (999) 123-45-67',
        'Email' => 'kirilukvladn@gmail.com',
        'Адрес' => 'г. Москва, ул. Малая Семеновская, 12',
        'Телеграмм' => '@Irid503'
    ];

    return view('contacts', ['contacts' => $contacts]);
})->name('contacts');

/* Регистрация и вход — только для гостей, авторизованного middleware guest отправит на главную. */
Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthController::class, 'create'])->name('signin');
    Route::post('/signin', [AuthController::class, 'registration'])->name('registration');

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');
});

/* Всё, что ниже, доступно только авторизованным пользователям. */
Route::middleware('auth:sanctum')->group(function () {
    /* index, create, store, show, edit, update, destroy */
    Route::resource('articles', ArticleController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
