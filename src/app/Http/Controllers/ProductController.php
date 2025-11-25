<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // 検索ワード取得

        // 🔽 商品を検索＆ログインユーザーの商品を除外
        $products = Product::when(
            $search,
            fn ($query) => $query->where('name', 'like', "%{$search}%")
        )
            ->where(function ($query) {
                $query->where('user_id', '<>', Auth::id())
                    ->orWhereNull('user_id')
                    ->orWhere('user_id', 0); // 共通商品を含める
            })
            ->get();

        // ビューへデータを渡す
        return view('products.index', compact('products'));
    }
}
