@extends('layouts.app_loggedin')

@section('title', '商品の出品')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mypage/sell.css') }}">
<style>
    /* 選択時に色が変わる */
    .tag.selected {
        background-color: #007bff;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="sell-container">
    <h1 class="sell-title">商品の出品</h1>

    <form action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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

            {{-- カテゴリー（見た目はタグ風＋複数選択可） --}}
            <div class="form-group">
                <label class="form-label">カテゴリー</label>
                <div class="category-tags" id="category-tags">
                    @php
                        $categories = [
                            'ファッション', '家電', 'インテリア', 'レディース', 'メンズ', 'コスメ', '本', 'ゲーム',
                            'スポーツ', 'キッチン', 'ハンドメイド', 'アクセサリー', 'おもちゃ', 'ベビー・キッズ'
                        ];
                    @endphp
                    @foreach($categories as $category)
                        <span class="tag" data-value="{{ $category }}">{{ $category }}</span>
                    @endforeach
                </div>
                {{-- 選択されたカテゴリーを格納する hidden input --}}
                <input type="hidden" name="categories" id="selected-categories">
            </div>

            {{-- 商品の状態 --}}
            <div class="form-group">
                <label class="form-label">商品の状態</label>
                <select name="condition" class="form-select">
                    <option value="">選択してください</option>
                    <option>良好</option>
                    <option>目立った傷や汚れなし</option>
                    <option>やや傷や汚れあり</option>
                    <option>状態が悪い</option>
                </select>
            </div>
        </section>

        {{-- 商品名と説明 --}}
        <section class="form-section">
            <h2 class="section-title">商品名と説明</h2>

            <div class="form-group">
                <label class="form-label">商品名</label>
                <input type="text" name="name" class="form-input" placeholder="商品名を入力してください">
            </div>

            <div class="form-group">
                <label class="form-label">ブランド名</label>
                <input type="text" name="brand" class="form-input" placeholder="ブランド名を入力してください">
            </div>

            <div class="form-group">
                <label class="form-label">商品の説明</label>
                <textarea name="description" class="form-textarea" placeholder="商品の説明を入力してください"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">販売価格</label>
                <div class="price-input">
                    <span>¥</span>
                    <input type="number" name="price" class="form-input" placeholder="0">
                </div>
            </div>
        </section>

        {{-- 出品ボタン --}}
        <div class="submit-box">
            <button type="submit" class="submit-btn">出品する</button>
        </div>
    </form>
</div>

{{-- タグ選択スクリプト --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tags = document.querySelectorAll('.tag');
        const input = document.getElementById('selected-categories');
        const selected = new Set();

        tags.forEach(tag => {
            tag.addEventListener('click', () => {
                const value = tag.dataset.value;
                if (selected.has(value)) {
                    selected.delete(value);
                    tag.classList.remove('selected');
                } else {
                    selected.add(value);
                    tag.classList.add('selected');
                }
                input.value = Array.from(selected).join(',');
            });
        });
    });
</script>
@endsection
