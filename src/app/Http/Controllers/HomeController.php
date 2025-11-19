<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $search = $request->query('q'); // 現在の検索ワード

        // ===========================================
        // 🔹 検索欄の状態管理ロジック
        // ===========================================
        if ($request->has('q')) {
            // 検索フォームから送信されたとき
            if (trim($search) === '') {
                // 空文字（またはスペースだけ）ならリセット
                session()->forget('q');
                $search = null;
            } else {
                // 入力ありならセッションに保存
                session(['q' => $search]);
            }
        } else {
            // 検索フォーム未送信（タブ切り替えなど）時は前回の検索語を維持
            $search = session('q');
        }

        // ===========================================
        // 🔹 検索 + タブ別のデータ取得
        // ===========================================
        if ($tab === 'mylist') {
            // マイリスト（お気に入り）
            $items = Item::whereHas('favorites', function ($query) {
                $query->where('user_id', Auth::id());
            })
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                })
                ->get();
        } else {
            // ==========================
            // 🔹 おすすめタブ
            // ==========================

            // itemsテーブル（初期登録データ）
            $seededItems = Item::when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })->get();

            // productsテーブル（他ユーザー出品商品、自分は除外）
            $userProducts = Product::when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
                ->where('user_id', '!=', Auth::id())
                ->get()
                ->map(function ($product) {
                    return (object) [
                        'id' => $product->id,
                        'name' => $product->name,
                        'img_url' => $product->img_url,
                        'is_favorite' => false,
                        'purchases' => collect(),
                    ];
                });

            // 両方を結合
            $items = $seededItems->concat($userProducts);
        }

        return view('home.index', compact('tab', 'items', 'search'));
    }
}
