<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/* Лаба 15: копия AuthController для REST API. Форм (create, login) здесь нет —
   клиент сам отправляет данные, а в ответ получает json и токен Sanctum. */
class AuthController extends Controller
{
    /* Русские сообщения об ошибках, общие для регистрации и входа. */
    private function messages(): array
    {
        return [
            'required' => 'Поле «:attribute» обязательно для заполнения.',
            'email' => 'Введите корректный email.',
            'unique' => 'Пользователь с таким email уже зарегистрирован.',
            'min' => 'Поле «:attribute» должно быть не короче :min символов.',
            'max' => 'Поле «:attribute» должно быть не длиннее :max символов.',
            'confirmed' => 'Пароли не совпадают.',
        ];
    }

    /* POST /api/register — проверяет данные и сохраняет нового пользователя в БД. */
    public function registration(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], $this->messages(), [
            'name' => 'Имя',
            'email' => 'Email',
            'password' => 'Пароль',
        ]);

        /* Все новые пользователи регистрируются как читатели. */
        $reader = Role::where('name', 'reader')->first();

        /* Пароль отдельно не хешируем — в модели User стоит cast 'hashed'. */
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role_id' => $reader->id,
        ]);

        return response()->json([
            'message' => 'Регистрация прошла успешно, теперь войдите',
            'user' => $user,
        ], 201);
    }

    /* POST /api/login — проверяет логин/пароль и выдаёт токен Sanctum.
       Дальше токен передаётся в заголовке Authorization: Bearer <токен>. */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], $this->messages(), [
            'email' => 'Email',
            'password' => 'Пароль',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json(['message' => 'Неверный email или пароль.'], 401);
        }

        /* Старые токены удаляем, чтобы у пользователя был один актуальный. */
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Вход выполнен',
            'user' => $user->load('role'),
            'token' => $token,
        ]);
    }

    /* POST /api/logout — удаляем токен, с ним больше нельзя обращаться к API. */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Вы вышли из системы']);
    }
}
