<?php

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