@php use Illuminate\Support\Str; @endphp

@extends('layouts.app_loggedin')

@section('title', $productOrItem->name . ' | 商品詳細')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/items/item_detail.css') }}">
@endpush

@section('content')
<div class="item-detail-container">

    {{-- 左側：商品画像 --}}
    <div class="item-image">
        @php
            $imageUrl = $productOrItem->img_url
                ?? $productOrItem->image
                ?? $productOrItem->image_path
                ?? null;

            $isExternal = $imageUrl && preg_match('/^https?:\/\//', $imageUrl);
        @endphp

        @if ($imageUrl)
            <img
                src="{{ $isExternal ? $imageUrl : asset(Str::startsWith($imageUrl, 'storage/') ? $imageUrl : 'storage/' . ltrim($imageUrl, '/')) }}"
                alt="{{ $productOrItem->name }}"
                class="item-detail-image"
            >
        @else
            <p class="text-gray-500">画像がありません</p>
        @endif


    </div>

    {{-- 右側：商品情報 --}}
    <div class="item-info">
        <h2 class="item-name">{{ $productOrItem->name }}</h2>
        <p class="item-brand">{{ $productOrItem->brand ?? 'ブランド名' }}</p>
        <p class="item-price">¥{{ number_format($productOrItem->price) }} <span>(税込)</span></p>

        <div class="item-actions">
            <div class="like-comment">
                <span>⭐ {{ $productOrItem->favorites_count ?? ($productOrItem->favorites->count() ?? 0) }}</span>
                <span>💬 {{ $productOrItem->comments->count() ?? 0 }}</span>
            </div>

            <a href="{{ url('/purchase/' . $productOrItem->id . '?type=' . $type) }}" class="btn-purchase">購入手続きへ</a>

        </div>

        <h3 class="section-title">商品説明</h3>
        <div class="item-description">
            {!! nl2br(e($productOrItem->description ?? '商品の説明がここに入ります。')) !!}
        </div>

        <h3 class="section-title">商品情報</h3>
        <div class="item-meta">
            <p>カテゴリー：
                <span class="tag">
                    @if($type === 'item')
                        {{ $productOrItem->category->name ?? '未分類' }}
                    @else
                        {{ $productOrItem->categories ?? '未分類' }}
                    @endif
                </span>
            </p>
            <p>商品の状態：{{ $productOrItem->condition ?? '良好' }}</p>
        </div>

        <h3 class="section-title">コメント ({{ $productOrItem->comments->count() ?? 0 }})</h3>
        <div class="comments">
            @forelse($productOrItem->comments as $comment)
                <div class="comment">
                    <div class="comment-user">{{ $comment->user->name ?? '匿名' }}</div>
                    <div class="comment-body">{{ $comment->content }}</div>
                </div>
            @empty
                <p>コメントはまだありません。</p>
            @endforelse
        </div>

       {{-- コメントフォーム --}}
        <form
            action="{{ route('comments.store', ['type' => $type, 'id' => $productOrItem->id]) }}"
            method="POST"
            class="comment-form"
        >
            @csrf
            <label for="content">商品へのコメント</label>
            <textarea name="content" id="content" placeholder="コメントを入力してください">{{ old('content') }}</textarea>

            @error('content')
                <p class="error-message">{{ $message }}</p>
            @enderror

            <button type="submit" class="btn-comment">コメントを送信する</button>
        </form>



    </div>
</div>
@endsection
