@extends('layouts.app_loggedin')

@section('title', '商品一覧')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endpush

@section('content')
<div class="tab-menu">
    <a href="{{ url('/?tab=recommend') }}" class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
    <a href="{{ url('/?tab=mylist') }}" class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
</div>

<div class="item-list">
    @foreach($items as $item)
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

            @if($tab === 'recommend')
            <form action="{{ route('favorite.store', ['item_id' => $item->id]) }}" method="POST" class="favorite-form">
                @csrf
                <button type="submit" class="favorite-btn {{ $item->is_favorite ? 'active' : '' }}">
                    <i class="fa{{ $item->is_favorite ? 's' : 'r' }} fa-heart"></i>
                </button>
            </form>
            @elseif($tab === 'mylist')
            <form action="{{ route('favorite.destroy', ['item_id' => $item->id]) }}" method="POST" class="favorite-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="favorite-btn active">
                    <i class="fas fa-heart"></i>
                </button>
            </form>
            @endif
        </div>
    @endforeach

    @if($tab === 'mylist' && count($items) === 0)
        <div class="no-item">お気に入りはまだありません。</div>
    @endif
</div>

{{-- FontAwesome（ハートアイコン用） --}}
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
@endsection
