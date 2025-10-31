@extends('layouts.app')

@section('title', '会員登録')

@section('content')
    {{-- register.css を直接ここで読み込む --}}
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">

    <div class="register-container">
        <h2 class="register-title">会員登録</h2>

        <form action="{{ route('register.post') }}" method="POST" class="register-form" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">ユーザー名</label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">メールアドレス</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </div>

            {{-- パスワード --}}
<div class="form-group">
    <label for="password">パスワード</label>
    <input type="password" id="password" name="password">
    @error('password')
        <p class="error">{{ $message }}</p>
    @enderror
</div>

{{-- 確認用パスワード --}}
<div class="form-group">
    <label for="password_confirmation">確認用パスワード</label>
    <input type="password" id="password_confirmation" name="password_confirmation">
    @error('password_confirmation')
        <p class="error">{{ $message }}</p>
    @enderror
</div>




            <button type="submit" class="register-btn">登録する</button>
        </form>

        <div class="login-link">
            <a href="{{ route('login') }}">ログインはこちら</a>
        </div>
    </div>
@endsection
