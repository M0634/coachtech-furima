@extends('layouts.app')

@section('title', 'メール認証')

@section('content')
<link rel="stylesheet" href="{{ asset('css/auth/verify.css') }}">

<div class="verify-container">
    <p class="verify-message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール内のリンクをクリックして認証を完了してください。
    </p>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="verify-button-area">
        <a href="http://localhost:8025" class="verify-button" target="_blank">
            認証はこちらから
        </a>
    </div>


    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link">認証メールを再送する</button>
    </form>
</div>
@endsection
