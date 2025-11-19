<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * お気に入りトグル（Item専用）
     */
    public function toggle(Request $request)
    {
        $user = Auth::user();
        $itemId = $request->input('id');

        // 既にお気に入り登録されているかチェック
        $favorite = Favorite::where('user_id', $user->id)
                            ->where('item_id', $itemId)
                            ->first();

        if ($favorite) {
            // お気に入り解除
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        }

        // お気に入り追加
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $itemId,
        ]);

        return response()->json(['status' => 'added']);
    }
}
