@extends('layouts.app_loggedin')

@section('title', 'プロフィール設定')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endpush

@section('content')
<div class="profile-container">
    <h2 class="profile-title">プロフィール設定</h2>

    <form action="{{ route('mypage.profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        <div class="profile-image-area">
            <div class="profile-image-preview" id="preview-wrapper">
                <img id="preview"
                    src="{{ $profile && $profile->image ? asset('storage/' . $profile->image) : '' }}"
                    alt="プロフィール画像">
            </div>
            <label class="image-select-btn">
                画像を選択する
                <input type="file" name="image" id="image" accept="image/*" hidden>
            </label>
        </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input type="text" id="name" name="name" value="{{ old('name', $profile->name ?? '') }}">
        </div>

        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" value="{{ old('address', $profile->address ?? '') }}">
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" id="building" name="building" value="{{ old('building', $profile->building ?? '') }}">
        </div>

        <button type="submit" class="update-btn">更新する</button>
    </form>
</div>

<script>
const imageInput = document.getElementById('image');
const previewImg = document.getElementById('preview');

imageInput.addEventListener('change', function(e) {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewImg.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewImg.src = '';
        previewImg.style.display = 'none';
    }
});
</script>
@endsection
