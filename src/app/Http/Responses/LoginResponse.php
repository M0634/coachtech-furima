<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Illuminate\Support\Facades\Auth;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        /**
         * first_login_verified の意味
         * 0 = 会員登録直後
         * 1 = 2回目ログイン（メール認証必須）
         * 2 = 3回目以降（通常ログイン）
         */

        // =============================
        // 会員登録直後
        // =============================
        if ($user->first_login_verified === 1 && ! $user->hasVerifiedEmail()) {
            // 初回ログイン（DBは authenticateUsing で 1 に更新済み）
            return redirect()->route('verification.notice');
        }

        // =============================
        // 2回目ログイン
        // =============================
        if ($user->first_login_verified === 1 && $user->hasVerifiedEmail()) {
            // 2回目ログインかつメール認証済 → DBを2に更新してマイページ
            $user->first_login_verified = 2;
            $user->save();
            return redirect()->intended('/mypage');
        }

        // =============================
        // 3回目以降
        // =============================
        if ($user->first_login_verified === 2) {
            if (! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }
            return redirect()->intended('/mypage');
        }

        // 念のためのフォールバック
        return redirect()->route('login');
    }
}
