<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['bail', 'required', 'string', 'max:255', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => '名前を入力してください',
            'name.string' => '名前を入力してください',
            'name.max' => '名前は255文字以内で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.string' => 'メールの形式が違います。直してください',
            'email.email' => 'メールの形式が違います。直してください',
            'email.max' => 'メールアドレスは255文字以内で入力してください',
            'email.unique' => 'このメールアドレスは既に登録済みです',
            'password.required' => 'パスワードを入力してください',
            'password.string' => 'パスワードは８文字以上で入力してください',
            'password.min' => 'パスワードは８文字以上で入力してください',
            'password.confirmed' => 'パスワードが違います',
        ]);

        if ($validator->fails()) {
            return redirect('/register')->withErrors($validator)->withInput($request->only('name', 'email'));
        }

        $user = User::create($validator->validated());
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/entries');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $valid = Validator::make($credentials, [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ])->passes();

        if (! $valid || ! Auth::attempt($credentials)) {
            return redirect('/login')->withErrors([
                'email' => 'メールアドレスかパスワードが違います',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect('/entries');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
