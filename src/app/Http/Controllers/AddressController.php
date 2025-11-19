<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        $profile = auth()->user()->address ?? null;
        $item = Item::findOrFail($item_id);

        return view('purchase.address', compact('profile', 'item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $user = auth()->user();

        $profile = $user->address;
        if ($profile) {
            $profile->update($request->validated());
        } else {
            $profile = $user->address()->create($request->validated());
        }

        return redirect()
            ->route('purchase.show', [
                'item_id' => $item_id,
                'type' => $request->input('type', 'item'),
            ])
            ->with('success', '住所を更新しました。');

    }
}
