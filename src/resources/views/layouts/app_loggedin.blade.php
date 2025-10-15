<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'COACHTECH')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
    {{-- ヘッダー --}}
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="logo">
        </div>

        <div class="header-center">
            <input type="text" placeholder="なにをお探しですか？" class="search-box">
        </div>

        <div class="header-right">
            {{-- ▼ ログイン状態で表示 --}}
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

            {{-- ▼ ログインしていない場合 --}}
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
