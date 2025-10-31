<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'COACHTECH')</title>

    <link rel="stylesheet" href="{{ asset('css/layouts/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('styles')
</head>
<body>
    <header class="header">
        <div class="header-left">
            <a href="{{ route('home') }}">
                <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="logo">
            </a>
        </div>
        <div class="header-center">
            <form action="{{ route('home') }}" method="GET" class="search-form">
                {{-- 現在のタブが未定義でも安全 --}}
                <input type="hidden" name="tab" value="{{ request('tab', 'recommend') }}">

                {{-- 検索ボックス（タブ切り替え後も入力値を保持） --}}
                <input
                    type="text"
                    name="q"
                    value="{{ old('q', request('q', session('q', ''))) }}"
                    placeholder="なにをお探しですか？"
                    class="search-box"
                >

                {{-- 送信ボタン --}}
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>


        <div class="header-right">
            @auth
                <a href="{{ route('logout') }}" class="nav-link"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ログアウト
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>

                <a href="{{ route('mypage') }}" class="nav-link">マイページ</a>
                <a href="{{ route('sell') }}" class="nav-link">出品</a>
            @endauth

            @guest
                <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                <a href="{{ route('register') }}" class="nav-link">マイページ</a>
                <a href="{{ route('login') }}" class="nav-link">出品</a>
            @endguest
        </div>
    </header>

    {{-- メインコンテンツ --}}
    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
