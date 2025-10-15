<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class MypageController extends Controller
{
    /**
     * マイページトップ（出品・購入タブ切り替え）
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // タブ指定（デフォルトは「sold」＝出品した商品）
        $tab = $request->query('tab', 'sold');

        // 出品した商品（itemsテーブル）
        $soldItems = \App\Models\Item::where('user_id', $user->id)->get();

        // 購入した商品（purchasesテーブル経由で items を取得）
        $purchasedItems = \App\Models\Item::whereHas('purchases', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        return view('mypage.mypage', compact('user', 'tab', 'soldItems', 'purchasedItems'));
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
            'name'        => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:10',
            'address'     => 'nullable|string|max:255',
            'building'    => 'nullable|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        // プロフィール画像アップロード
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('avatars', 'public');
            $validated['image'] = $path;
        }

        // ユーザー情報更新
        $user->update($validated);

        return back()->with('message', 'プロフィールを更新しました！');
    }
}
