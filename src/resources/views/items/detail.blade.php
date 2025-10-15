@extends('layouts.app_loggedin')

@section('title', $item->name . ' | 商品詳細')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endpush

@section('content')
<div class="item-detail-container">

    {{-- 左側：商品画像 --}}
    <div class="item-image">
        @if($item->img_url)
            <img src="{{ $item->img_url }}" alt="{{ $item->name }}">
        @else
            <div class="no-image">商品画像</div>
        @endif
    </div>

    {{-- 右側：商品情報 --}}
    <div class="item-info">
        <h2 class="item-name">{{ $item->name }}</h2>
        <p class="item-brand">{{ $item->brand ?? 'ブランド名' }}</p>
        <p class="item-price">¥{{ number_format($item->price) }} <span>(税込)</span></p>

        <div class="item-actions">
            <div class="like-comment">
                <span>⭐ {{ $item->likes_count ?? 0 }}</span>
                <span>💬 {{ $item->comments->count() ?? 0 }}</span>
            </div>

            <a href="{{ url('/purchase/' . $item->id) }}" class="btn-purchase">購入手続きへ</a>
        </div>

        <h3 class="section-title">商品説明</h3>
        <div class="item-description">
            {!! nl2br(e($item->description ?? '商品の説明がここに入ります。')) !!}
        </div>

        <h3 class="section-title">商品情報</h3>
        <div class="item-meta">
            <p>カテゴリー：<span class="tag">{{ $item->category->name ?? '未分類' }}</span></p>
            <p>商品の状態：{{ $item->condition ?? '良好' }}</p>
        </div>

        <h3 class="section-title">コメント ({{ $item->comments->count() ?? 0 }})</h3>
        <div class="comments">
            @forelse($item->comments as $comment)
                <div class="comment">
                    <div class="comment-user">{{ $comment->user->name ?? '匿名' }}</div>
                    <div class="comment-body">{{ $comment->content }}</div>
                </div>
            @empty
                <p>コメントはまだありません。</p>
            @endforelse
        </div>

        {{-- コメントフォーム --}}
        <form action="{{ route('comments.store', $item) }}" method="POST" class="comment-form">
            @csrf
            <label for="content">商品へのコメント</label>
            <textarea name="content" id="content" rows="3" required></textarea>
            <button type="submit" class="btn-comment">コメントを送信する</button>
        </form>
    </div>
</div>
@endsection
