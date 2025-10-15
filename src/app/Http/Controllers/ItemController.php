<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            // マイリスト：DBからログインユーザーのお気に入り商品
            $items = Item::whereHas('favorites', function ($q) {
                $q->where('user_id', auth()->id());
            })->get();
        } else {
            // おすすめ：public/images の全画像を表示
            $imageFiles = [
                'Armani-Mens-Clock.jpg',
                'HDD-Hard-Disk.jpg',
                'iLoveIMG-d.jpg',
                'Leather-Shoes-Product-Photo.jpg',
                'Living-Room-Laptop.jpg',
                'makeup.jpg',
                'Music-Mic-4632231.jpg',
                'Purse-fashion-pocket.jpg',
                'Tumbler-souvenir.jpg',
                'Waitress-with-Coffee-Grinder.jpg',
            ];

            // オブジェクトに変換
            $items = collect();
            foreach ($imageFiles as $index => $file) {
                $items->push((object)[
                    'id' => $index + 1,
                    'name' => pathinfo($file, PATHINFO_FILENAME),
                    'img_url' => asset('images/' . $file),
                    'is_favorite' => false, // ダミー
                ]);
            }
        }

        return view('home.index', compact('tab', 'items'));
    }

    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('items.detail', compact('item'));
    }
}
