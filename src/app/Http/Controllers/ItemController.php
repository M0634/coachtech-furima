<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $search = $request->query('q', ''); // 検索ワード

        if ($tab === 'mylist') {
            // マイリスト：ログインユーザーのお気に入り商品
            $items = Item::whereHas('favorites', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->get();
        } else {
            // おすすめタブ：items テーブルから取得
            $items = Item::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })->get();

            // お気に入り判定と img_url 設定
            $items->transform(function ($item) {
                $item->is_favorite = auth()->check()
                    ? $item->favorites()->where('user_id', auth()->id())->exists()
                    : false;

                // img_url が空なら image_path や image カラムを使う
                $item->img_url = $item->img_url ?? $item->image_path ?? $item->image ?? '';

                return $item;
            });
        }

        return view('home.index', compact('tab', 'items', 'search'));
    }

    // 商品詳細
    public function show($item_id)
    {
        $item = Item::with(['comments.user', 'category', 'favorites']) // ← likes ではなく favorites を使うならここ変更
                    ->withCount('favorites') // ← いいね数（お気に入り数）を取得
                    ->findOrFail($item_id);  // ← $id → $item_id に修正

        return view('items.detail', compact('item'));
    }

}
