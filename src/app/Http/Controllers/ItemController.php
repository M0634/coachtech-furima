<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Favorite;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ItemController extends Controller
{
    /**
     * 商品一覧表示（Item + Product）
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');
        $search = $request->query('q', '');

        // ===== おすすめタブ =====
        if ($tab === 'recommend') {
            // Item一覧（自分の出品を除外）
            $items = Item::when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
                ->when(Auth::check(), fn ($query) => $query->where('user_id', '<>', Auth::id()))
                ->get();

            foreach ($items as $item) {
                $item->type = 'item';
                $item->is_favorite = Auth::check()
                    ? Favorite::where('user_id', Auth::id())
                        ->where('favoritable_type', Item::class)
                        ->where('favoritable_id', $item->id)
                        ->exists()
                    : false;
                $item->img_url = $item->img_url ?? $item->image_path ?? $item->image ?? '';
            }

            // Product一覧（自分の出品を除外）
            $products = Product::when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
                ->when(Auth::check(), fn ($query) => $query->where('user_id', '<>', Auth::id()))
                ->get();

            foreach ($products as $product) {
                $product->type = 'product';
                $product->is_favorite = Auth::check()
                    ? Favorite::where('user_id', Auth::id())
                        ->where('favoritable_type', Product::class)
                        ->where('favoritable_id', $product->id)
                        ->exists()
                    : false;
                $product->img_url = $product->img_url ?? $product->image ?? '';
            }

            // ===== マイリストタブ =====
        } elseif ($tab === 'mylist' && Auth::check()) {
            // ユーザーのお気に入りItemのみ取得
            $favoriteItemIds = Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', Item::class)
                ->pluck('favoritable_id');

            $items = Item::whereIn('id', $favoriteItemIds)
                ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
                ->get();

            foreach ($items as $item) {
                $item->type = 'item';
                $item->is_favorite = true;
                $item->img_url = $item->img_url ?? $item->image_path ?? $item->image ?? '';
            }

            // ユーザーのお気に入りProductのみ取得
            $favoriteProductIds = Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', Product::class)
                ->pluck('favoritable_id');

            $products = Product::whereIn('id', $favoriteProductIds)
                ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
                ->get();

            foreach ($products as $product) {
                $product->type = 'product';
                $product->is_favorite = true;
                $product->img_url = $product->img_url ?? $product->image ?? '';
            }

        } else {
            // デフォルト：おすすめ扱い（空コレクション）
            $items = collect();
            $products = collect();
        }

        // Item + Product を統合
        $items = $items->concat($products);

        return view('home.index', compact('tab', 'items', 'search'));
    }

    /**
     * お気に入りトグル
     */
    public function toggleFavorite(Request $request)
    {
        $user = Auth::user();
        $id = $request->input('id');
        $type = $request->input('type');

        $model = $type === 'product' ? \App\Models\Product::class : \App\Models\Item::class;

        $existing = \App\Models\Favorite::where('user_id', $user->id)
            ->where('favoritable_type', $model)
            ->where('favoritable_id', $id)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            \App\Models\Favorite::create([
                'user_id' => $user->id,
                'favoritable_type' => $model,
                'favoritable_id' => $id,
            ]);
        }

        return back();
    }

    /**
     * 商品詳細表示（Item or Product）
     */
    public function showItemOrProduct($type, $id)
    {
        if ($type === 'product') {
            $productOrItem = Product::findOrFail($id);
            $modelClass = Product::class;
        } else {
            $productOrItem = Item::findOrFail($id);
            $modelClass = Item::class;
        }

        // コメント取得
        $productOrItem->comments = Comment::where('commentable_type', $modelClass)
            ->where('commentable_id', $id)
            ->with('user')
            ->get();

        // お気に入り判定
        $productOrItem->is_favorite = Auth::check()
            ? Favorite::where('user_id', Auth::id())
                ->where('favoritable_type', $modelClass)
                ->where('favoritable_id', $id)
                ->exists()
            : false;

        return view('items.detail', [
            'productOrItem' => $productOrItem,
            'type' => $type,
        ]);
    }

    public function show($id)
    {
        // Item を優先して探す
        if (Item::find($id)) {
            return $this->showItemOrProduct('item', $id);
        }

        // なければ Product を探す
        if (Product::find($id)) {
            return $this->showItemOrProduct('product', $id);
        }

        abort(404, '指定された商品は存在しません');
    }

    /**
     * コメント保存
     */
    public function storeComment(Request $request, $type, $id)
    {
        // 認証チェック
        $user = Auth::user();
        if (!$user || !User::where('id', $user->id)->exists()) {
            return redirect('/login');
        }

        // バリデーション
        $request->validate([
            'content' => 'required|string|max:255',  // あなたの仕様に合わせる
        ]);

        // モデル決定
        $modelClass = $type === 'product' ? Product::class : Item::class;

        // item_id を残したいなら（Item の場合のみ保持）
        $itemId = $type === 'item' ? $id : null;

        // コメント作成
        Comment::create([
            'item_id'          => $itemId,
            'user_id'          => $user->id,
            'content'          => $request->input('content'),
            'commentable_type' => $modelClass,
            'commentable_id'   => $id,
        ]);

        return redirect()->back()->with('success', 'コメントを投稿しました。');
    }

}
