<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
     // ログイン画面を表示
    public function showLoginForm()
    {
        return view('auth.login');  // resources/views/auth/login.blade.php を表示
    }

    // 実際のログイン処理
    public function login(Request $request)
    {
        // ログイン処理をここに書く
    }
}
