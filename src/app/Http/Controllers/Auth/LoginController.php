<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // バリデーション
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 認証試行
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // セッション再生成
            $request->session()->regenerate();

            // ✅ ① 会員登録直後（first_login_verified = 0）
            // → まだ登録直後の状態として記録しておく（この段階ではメール認証誘導済み）
            if ($user->first_login_verified === 0) {
                // 1回目のログインを記録
                $user->first_login_verified = 1;
                $user->save();

                // 登録直後は今まで通りメール認証へ誘導
                return redirect()->route('verification.notice');
            }

            // ✅ ② 2回目のログイン（前回で first_login_verified=1 に更新済み）
            // → メール認証未済でも必ず /email/verify にリダイレクト
            if ($user->first_login_verified === 1 && !$user->hasVerifiedEmail()) {
                // 2回目のログインで初めてメール認証が必要
                $user->first_login_verified = 2;
                $user->save();

                return redirect()->route('verification.notice');
            }

            // ✅ ③ 3回目以降
            // → メール認証済みなら通常ログインOK
            if ($user->hasVerifiedEmail()) {
                return redirect()->intended('/home');
            }

            // ✅ ④ 認証していないのにログインしようとした場合
            Auth::logout();
            return redirect()->route('verification.notice');
        }

        // ログイン失敗時
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
