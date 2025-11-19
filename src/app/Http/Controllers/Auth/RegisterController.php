<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash; // ← 追加

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Request ではなく RegisterRequest を使う
    public function register(RegisterRequest $request)
    {
        // RegisterRequest でバリデーション済みの値だけを取得
        $validated = $request->validated();

        // ユーザー作成
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        // 登録後自動ログイン
        auth()->login($user);

        // メール認証画面へリダイレクト
        return redirect()->route('verification.notice');
    }
}
