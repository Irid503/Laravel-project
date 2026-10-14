<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /* Отдаёт клиенту страницу с формой регистрации. */
    public function create()
    {
        return view('auth.signin');
    }

    /* Проверяет данные из формы и сохраняет нового пользователя в БД. */
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

        /* Пароль отдельно не хешируем — в модели User стоит cast 'hashed'. */
        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        return redirect()->route('login')->with('message', 'Регистрация прошла успешно, теперь войдите');
    }

    /* Отдаёт клиенту страницу с формой входа. */
    public function login()
    {
        return view('auth.login');
    }

    /* Проверяет логин/пароль, создаёт сессию и выдаёт пользователю токен Sanctum. */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], $this->messages(), [
            'email' => 'Email',
            'password' => 'Пароль',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Неверный email или пароль.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        /* Старые токены удаляем, чтобы у пользователя был один актуальный. */
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        $request->session()->put('auth_token', $token);

        /* Сразу на главную, а не на intended-страницу, с которой перекинул middleware auth. */
        return redirect()->route('home');
    }

    /* Выход: удаляем токен, аннулируем сессию и обновляем csrf-токен. */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
