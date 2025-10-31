<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\Item;

class AddressController extends Controller
{
    // 編集フォーム表示
    public function edit($item_id)
    {
        $profile = auth()->user()->profile;
        $item = Item::findOrFail($item_id);

        return view('purchase.address', compact('profile', 'item'));
    }

    // 更新処理
    public function update(Request $request, $item_id)
    {
        $request->validate([
            'postal_code' => 'required|string|max:10',
            'address' => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ]);

        $profile = auth()->user()->profile;
        $profile->update([
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        // ✅ 修正ポイント：ルートパラメータを明示的に渡す
        return redirect()->route('purchase.show', ['item' => $item_id])
                         ->with('success', '住所を更新しました。');

    }
}
