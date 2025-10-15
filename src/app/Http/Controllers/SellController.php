<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SellController extends Controller
{
    // 出品ページを表示
    public function index()
    {
        return view('mypage.sell'); // ← sell.blade.php を読み込む
    }

    // 出品データを保存
    public function store(Request $request)
    {
        // 例: 簡単なバリデーション
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        // 仮保存（実際はモデルを使う）
        // Product::create($validated);

        return redirect()->route('mypage')->with('success', '商品を出品しました！');
    }
}
