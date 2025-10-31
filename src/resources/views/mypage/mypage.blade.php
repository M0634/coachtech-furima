@extends('layouts.app_loggedin')

@section('title', 'マイページ')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/mypage/mypage.css') }}">
@endpush

@section('content')
<div class="mypage-container">
    {{-- プロフィール情報 --}}
    <div class="profile-section">
        <div class="profile-avatar">
            @php
                $profile = Auth::user()->profile; // Profile情報を取得
            @endphp

            @if(!empty($profile) && !empty($profile->image))
                <img src="{{ asset('storage/' . $profile->image) }}" alt="" class="avatar-img">
            @else
                <img src="{{ asset('images/default-avatar.png') }}" alt="" class="avatar-img">
            @endif
        </div>

        <div class="profile-info">
            <h2 class="username">{{ $profile->name ?? Auth::user()->name }}</h2>

        </div>

        <a href="{{ url('/mypage/profile') }}" class="edit-profile-btn">プロフィールを編集</a>
    </div>

    {{-- ===== 商品一覧エリアを全幅に ===== --}}
    <div class="mypage-content-full">
        {{-- タブメニュー --}}
        <div class="tab-menu">
            <a href="{{ url('/mypage?page=sell') }}" class="{{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ url('/mypage?page=buy') }}" class="{{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>

        </div>

        <hr class="divider">

        {{-- 商品一覧 --}}
        <div class="item-list">
            @php
                $items = $page === 'sell' ? $soldItems : $purchasedItems;
            @endphp


            @forelse ($items as $item)
                <div class="item-card">
                    <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
                        <div class="item-image">
                            @if(!empty($item->img_url))
                                <img src="{{ $item->img_url }}" alt="{{ $item->name }}" class="item-img">
                            @else
                                <span>商品画像なし</span>
                            @endif
                        </div>
                        <div class="item-name">{{ $item->name }}</div>
                    </a>
                </div>
            @empty
                <p class="no-items">商品がありません。</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
