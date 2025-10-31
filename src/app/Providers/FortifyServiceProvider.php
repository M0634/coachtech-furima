<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // ==========================
        // Fortify ビュー設定
        // ==========================
        Fortify::loginView(fn() => view('auth.login'));
        Fortify::registerView(fn() => view('auth.register'));
        Fortify::requestPasswordResetLinkView(fn() => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn($request) => view('auth.reset-password', ['request' => $request]));
        Fortify::verifyEmailView(fn() => view('auth.verify-email'));
        Fortify::twoFactorChallengeView(fn() => view('auth.two-factor-challenge'));

        // ==========================
        // カスタム認証ロジック（パスワード認証のみ）
        // ==========================
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => 'メールアドレスまたはパスワードが正しくありません。',
                ]);
            }

            // 会員登録直後は 0 → 1 に更新
            if ($user->first_login_verified === 0) {
                $user->first_login_verified = 1;
                $user->save();
            }

            return $user;
        });

        // ==========================
        // ログイン試行制限
        // ==========================
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(10)->by($request->email . $request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(10)->by(optional($request->session())->get('login.id'));
        });
    }
}
