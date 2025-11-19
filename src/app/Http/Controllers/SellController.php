<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellController extends Controller
{
    // 出品ページ表示
    public function create()
    {
        return view('mypage.sell'); // sell.blade.php を返す
    }

    // 出品データ保存
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|max:2048',
            'categories' => 'nullable|string|max:255',
            'condition' => 'required|string',
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
        ]);

        $imgPath = null;
        if ($request->hasFile('image')) {
            $imgPath = $request->file('image')->store('products', 'public');
        }

        Product::create([
            'user_id' => Auth::id(), // 明示的に user_id を設定
            'img_url' => $imgPath,
            'categories' => $request->categories,
            'condition' => $request->condition,
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return redirect()->route('home')->with('success', '商品を出品しました');
    }
}
