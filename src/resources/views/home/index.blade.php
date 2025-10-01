@extends('layouts.app_loggedin')

@section('title', '商品一覧（トップページ）')

@section('content')
<div class="container">
    <h1>商品一覧</h1>

    {{-- 商品一覧をここに表示 --}}
    <div class="items">
        {{-- 例: foreachで商品を回す --}}
        @foreach($items ?? [] as $item)
            <div class="item-card">
                <h2>{{ $item->name }}</h2>
                <p>{{ $item->price }}円</p>
                <a href="{{ url('/item/' . $item->id) }}">詳細を見る</a>
            </div>
        @endforeach
    </div>
</div>
@endsection
