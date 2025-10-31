@extends('layouts.app_loggedin')

@section('title', '購入手続き')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase/mypage_buy.css') }}">
@endpush

@section('content')
<div class="buy-container">

    {{-- 左側：商品情報 --}}
    <div class="buy-left">
        <div class="item-summary">
            <div class="item-image">
                @php
                    $imageUrl = $item->img_url;
                    $isExternal = preg_match('/^https?:\/\//', $imageUrl);
                @endphp

                @if($imageUrl)
                    <img src="{{ $isExternal ? $imageUrl : asset($imageUrl) }}" alt="{{ $item->name }}">
                @else
                    <div class="no-image">商品画像</div>
                @endif
            </div>

            <div class="item-detail">
                <h2>{{ $item->name }}</h2>
                <p class="item-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <hr>

        {{-- 支払い方法 --}}
        <div class="payment-section">
            <h3>支払い方法</h3>
            <select name="payment_method" id="payment_method">
                <option value="">選択してください</option>
                <option value="コンビニ払い">コンビニ払い</option>
                <option value="クレジットカード">クレジットカード</option>
            </select>
        </div>

        <hr>

        {{-- 配送先 --}}
        <div class="address-section">
        <h3>配送先</h3>
        <div class="address">
            @if($profile)
                <p>〒 {{ $profile->postal_code ?? '未登録' }}</p>
                <p>{{ $profile->address ?? '未登録住所' }} {{ $profile->building ?? '' }}</p>
            @else
                <p>住所が登録されていません。</p>
            @endif
        </div>

    <a href="{{ route('purchase.address.edit', ['item_id' => $item->id]) }}" class="change-address">
        変更する
    </a>
</div>
        <hr>
    </div>

    {{-- 右側：購入内容確認 --}}
    <div class="buy-right">
        <div class="summary-box">
            <div class="summary-row">
                <span>商品代金</span>
                <span>¥{{ number_format($item->price) }}</span>
            </div>
            <div class="summary-row">
                <span>支払い方法</span>
                <span id="selected-method">コンビニ払い</span>
            </div>
        </div>

        <form action="{{ route('purchase.checkout', $item->id) }}" method="POST">
            @csrf
            <input type="hidden" name="payment_method" id="selected_method_input">
            <button type="submit" class="btn-buy">購入する</button>
        </form>

        <script>
            const methodSelect = document.getElementById('payment_method');
            const selectedMethodDisplay = document.getElementById('selected-method');
            const hiddenInput = document.getElementById('selected_method_input');

            methodSelect.addEventListener('change', function() {
                const method = this.value || '未選択';
                selectedMethodDisplay.textContent = method;
                hiddenInput.value = method;
            });
        </script>


    </div>
</div>

<script>
    // 支払い方法選択時に右側へ反映
    document.getElementById('payment_method').addEventListener('change', function() {
        document.getElementById('selected-method').textContent = this.value || '未選択';
    });
</script>
@endsection
