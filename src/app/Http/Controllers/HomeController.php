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
            $items = Item::when($search, function ($query) use ($search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->get();
        }

        return view('home.index', compact('tab', 'items', 'search'));
    }
}
