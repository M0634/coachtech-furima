<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
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
            <a href="#" class="nav-link">ログアウト</a>
            <a href="#" class="nav-link">マイページ</a>
            <a href="#" class="nav-link">出品</a>
                </div>
    </header>

    {{-- メインコンテンツ --}}
    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
