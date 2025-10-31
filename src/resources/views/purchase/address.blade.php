@extends('layouts.app_loggedin')

@section('title', '住所の変更')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/purchase/purchase_address.css') }}">
@endpush

@section('content')
<div class="address-edit-wrapper">
    <h1>住所の変更</h1>

    <form action="{{ route('purchase.address.update', ['item_id' => $item->id]) }}" method="POST" class="address-form">
        @csrf

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code"
                   value="{{ old('postal_code', $profile->postal_code) }}">
            @error('postal_code')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address"
                   value="{{ old('address', $profile->address) }}">
            @error('address')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building"
                   value="{{ old('building', $profile->building) }}">
        </div>

        <button type="submit" class="btn-submit">更新する</button>
    </form>
</div>
@endsection
