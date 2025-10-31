<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'COACHTECH')</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/style.css') }}">
    @stack('styles')
</head>
<body>
    {{-- ヘッダー --}}
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="logo">
        </div>
    </header>

    {{-- メインコンテンツ --}}
    <main class="main-content">
        @yield('content')
    </main>
</body>
</html>
