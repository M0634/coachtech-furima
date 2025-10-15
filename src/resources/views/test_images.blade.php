<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>画像テスト</title>
    <style>
        .image-list { display: flex; flex-wrap: wrap; gap: 20px; margin-top: 20px; }
        .image-card { width: 180px; text-align: center; }
        .image-card img { width: 100%; height: auto; border: 1px solid #ccc; border-radius: 8px; }
    </style>
</head>
<body>
    <h1>画像テストページ</h1>
    <div class="image-list">
        @foreach($images as $img)
            <div class="image-card">
                <img src="{{ asset('images/' . $img) }}" alt="{{ $img }}">
                <p>{{ $img }}</p>
            </div>
        @endforeach
    </div>
</body>
</html>
