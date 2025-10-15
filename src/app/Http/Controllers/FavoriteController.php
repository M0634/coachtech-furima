<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    // お気に入り登録
    public function store($item_id)
    {
        Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'item_id' => $item_id,
        ]);

        return back()->with('success', 'お気に入りに追加しました！');
    }

    // お気に入り解除
    public function destroy($item_id)
    {
        Favorite::where('user_id', Auth::id())
                ->where('item_id', $item_id)
                ->delete();

        return back()->with('success', 'お気に入りを解除しました。');
    }
}
