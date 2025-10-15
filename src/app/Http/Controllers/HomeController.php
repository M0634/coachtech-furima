<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            // ログイン中ユーザーのお気に入り商品を取得
            $items = Item::whereHas('favorites', function ($query) {
                $query->where('user_id', Auth::id());
            })->get();
        } else {
            // おすすめ一覧（全件 or 絞り込み可）
            $items = Item::all();
        }

        return view('home.index', compact('tab', 'items'));
    }
}
