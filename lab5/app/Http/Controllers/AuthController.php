<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    /* Отдаёт клиенту страницу с формой регистрации. */
    public function create()
    {
        return view('auth.signin');
    }

    /* Проверяет данные из формы и возвращает их в виде json. */
    public function registration(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
        ], [
            'required' => 'Поле «:attribute» обязательно для заполнения.',
            'email' => 'Введите корректный email.',
            'min' => 'Поле «:attribute» должно быть не короче :min символов.',
            'max' => 'Поле «:attribute» должно быть не длиннее :max символов.',
        ], [
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
        ]);

        /* JSON_UNESCAPED_UNICODE — чтобы русские буквы в ответе не превращались в И... */
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
}
