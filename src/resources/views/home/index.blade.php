@extends('layouts.app_loggedin')

@section('title', '商品一覧')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home/home.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<div class="container">
    {{-- ==============================
         タブメニュー
    =============================== --}}
    <div class="tab-menu">
        <a href="{{ url('/?tab=recommend') }}" class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ url('/?tab=mylist') }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    {{-- ==============================
         商品一覧
    =============================== --}}
    <div class="item-list">
        @forelse($items as $item)
            @php
                // ログインユーザーが購入済みかチェック
                $isSold = $item->purchases
                    ->where('user_id', auth()->id())
                    ->where('status', '購入済み')
                    ->isNotEmpty();
            @endphp

            <div class="item-card {{ $isSold ? 'sold' : '' }}">
                {{-- ==============================
                     お気に入りボタン
                =============================== --}}
                @if($item->is_favorite)
                    <form action="{{ route('favorite.destroy', ['item_id' => $item->id]) }}" method="POST" class="favorite-btn active">
                        @csrf
                        @method('DELETE')
                        <button type="submit">
                            <i class="fas fa-star"></i>
                        </button>
                    </form>
                @else
                    <form action="{{ route('favorite.store', ['item_id' => $item->id]) }}" method="POST" class="favorite-btn">
                        @csrf
                        <button type="submit">
                            <i class="far fa-star"></i>
                        </button>
                    </form>
                @endif

                {{-- ==============================
                     商品リンク
                =============================== --}}
                <a href="{{ route('items.show', ['item_id' => $item->id]) }}">
                    <div class="item-image">
                        @php
                            $imageUrl = $item->img_url;
                            $isExternal = preg_match('/^https?:\/\//', $imageUrl);
                        @endphp

                        @if($imageUrl)
                            <img src="{{ $isExternal ? $imageUrl : asset($imageUrl) }}" alt="{{ $item->name }}" class="item-img">
                        @else
                            <div class="no-image">商品画像なし</div>
                        @endif

                        @if($isSold)
                            <div class="sold-overlay">SOLD</div>
                        @endif
                    </div>

                    <div class="item-name">{{ $item->name }}</div>
                </a>
            </div>
        @empty
            <div class="no-item">
                @if($tab === 'mylist')
                    お気に入りはまだありません。
                @else
                    現在出品されている商品はありません。
                @endif
            </div>
        @endforelse
    </div>
</div>
@endsection
