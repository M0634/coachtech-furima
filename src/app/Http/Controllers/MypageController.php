<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    /**
     * マイページトップ（出品・購入タブ切り替え）
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // タブ指定（デフォルトは「sold」＝出品した商品）
        $page = $request->query('page', 'sell');

        // 出品した商品（Productモデルを使用）
        $soldItems = Product::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // 購入した商品（購入履歴）
        $purchasedItems = Item::whereHas('purchases', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->orderBy('created_at', 'desc')->get();

        return view('mypage.mypage', compact('user', 'page', 'soldItems', 'purchasedItems'));
    }

    /**
     * プロフィール編集画面
     */
    public function edit()
    {
        $user = Auth::user();

        return view('mypage.profile', compact('user'));
    }

    /**
     * プロフィール更新処理
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('avatars', 'public');
            $validated['image'] = $path;
        }

        $user->update($validated);

        return back()->with('message', 'プロフィールを更新しました！');
    }
}
