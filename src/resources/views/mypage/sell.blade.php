@extends('layouts.app_loggedin')

@section('title', '商品の出品')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endpush

@section('content')
<div class="sell-container">
    <h1 class="sell-title">商品の出品</h1>

    {{-- 商品画像 --}}
    <section class="form-section">
        <h2 class="form-label">商品画像</h2>
        <div class="image-upload-box">
            <label for="image" class="image-select-btn">画像を選択する</label>
            <input type="file" id="image" name="image" accept="image/*" hidden>
        </div>
    </section>

    {{-- 商品の詳細 --}}
    <section class="form-section">
        <h2 class="section-title">商品の詳細</h2>

        <div class="form-group">
            <label class="form-label">カテゴリー</label>
            <div class="category-tags">
                <span class="tag">ファッション</span>
                <span class="tag">家電</span>
                <span class="tag">インテリア</span>
                <span class="tag">レディース</span>
                <span class="tag">メンズ</span>
                <span class="tag">コスメ</span>
                <span class="tag">本</span>
                <span class="tag">ゲーム</span>
                <span class="tag">スポーツ</span>
                <span class="tag">キッチン</span>
                <span class="tag">ハンドメイド</span>
                <span class="tag">アクセサリー</span>
                <span class="tag">おもちゃ</span>
                <span class="tag">ベビー・キッズ</span>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">商品の状態</label>
            <select class="form-select">
                <option value="">選択してください</option>
                <option>新品・未使用</option>
                <option>未使用に近い</option>
                <option>目立った傷や汚れなし</option>
                <option>やや傷や汚れあり</option>
                <option>全体的に状態が悪い</option>
            </select>
        </div>
    </section>

    {{-- 商品名と説明 --}}
    <section class="form-section">
        <h2 class="section-title">商品名と説明</h2>

        <div class="form-group">
            <label class="form-label">商品名</label>
            <input type="text" class="form-input" placeholder="商品名を入力してください">
        </div>

        <div class="form-group">
            <label class="form-label">ブランド名</label>
            <input type="text" class="form-input" placeholder="ブランド名を入力してください">
        </div>

        <div class="form-group">
            <label class="form-label">商品の説明</label>
            <textarea class="form-textarea" placeholder="商品の説明を入力してください"></textarea>
        </div>

        <div class="form-group">
            <label class="form-label">販売価格</label>
            <div class="price-input">
                <span>¥</span>
                <input type="number" class="form-input" placeholder="0">
            </div>
        </div>
    </section>

    {{-- 出品ボタン --}}
    <div class="submit-box">
        <a href="/sell" class="submit-btn">出品する</a>
    </div>
</div>
@endsection
