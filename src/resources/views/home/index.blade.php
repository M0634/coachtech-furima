@extends('layouts.app_loggedin')

@section('title', '商品一覧')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home/home.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container">
    {{-- タブメニュー --}}
    @php
        // 現在の検索クエリを保持
        $queryParams = request()->except('tab'); // tab以外の全パラメータを維持
    @endphp

    <div class="tab-menu">
        <a href="{{ url('/?' . http_build_query(array_merge($queryParams, ['tab' => 'recommend']))) }}"
        class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>

        <a href="{{ url('/?' . http_build_query(array_merge($queryParams, ['tab' => 'mylist']))) }}"
        class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>


    {{-- 商品一覧 --}}
    <div class="item-list">
        @forelse($items as $item)
            @php
                $isSold = isset($item->purchases) && $item->purchases
                    ->where('user_id', auth()->id())
                    ->where('status', '購入済み')
                    ->isNotEmpty();

                $imageUrl = $item->img_url ?? null;
                if ($imageUrl && !preg_match('/^https?:\/\//', $imageUrl)) {
                    // img_url がすでに '/storage/' を含む場合は重複しないようにする
                    $imageUrl = str_contains($imageUrl, 'storage/')
                        ? asset(ltrim($imageUrl, '/'))
                        : asset('storage/' . ltrim($imageUrl, '/'));
                }

                $detailUrl = route('item.show', [
                    'type' => $item->type ?? 'item',
                    'id' => $item->id
                ]);
            @endphp

            <div class="item-card {{ $isSold ? 'sold' : '' }}">
                {{-- お気に入りボタン --}}
                <div class="favorite-btn {{ $item->is_favorite ? 'active' : '' }}" data-id="{{ $item->id }}" data-type="{{ $item->type }}">
                    <i class="{{ $item->is_favorite ? 'fas' : 'far' }} fa-star fa-2x"></i>
                </div>

                {{-- 商品リンク --}}
                <a href="{{ $detailUrl }}">
                    <div class="item-image">
                        @if($imageUrl)
                            <img src="{{ $imageUrl }}" alt="{{ $item->name }}" class="item-img">
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

{{-- JS をここに書く --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    const isLoggedIn = @json(auth()->check()); // ← ここでログイン状態を取得

    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (!isLoggedIn) {
                // 未ログインならログインページへリダイレクト
                window.location.href = "{{ route('login') }}";
                return;
            }

            const id = btn.dataset.id;
            const type = btn.dataset.type;

            fetch('/favorites/toggle', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id, type })
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                const icon = btn.querySelector('i');
                icon.classList.toggle('fas');
                icon.classList.toggle('far');
                btn.classList.toggle('active');
            })
            .catch(err => console.error(err));
        });
    });
});
</script>

@endsection
