@extends('layouts.app')

@section('title', 'ログイン')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endpush

@section('content')
<div class="login-container">
    <h2 class="login-title">ログイン</h2>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        {{-- メールアドレス --}}
        <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        {{-- パスワード --}}
        <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <span class="error">{{ $message }}</span>
            @enderror
        </div>

        {{-- ログインボタン --}}
        <button type="submit" class="login-btn">ログインする</button>
    </form>

    {{-- 会員登録リンク --}}
    <p class="register-link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </p>
</div>
@endsection
