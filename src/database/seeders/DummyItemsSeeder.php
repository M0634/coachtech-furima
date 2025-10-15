<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class DummyItemsSeeder extends Seeder
{
    public function run()
    {
        $dummyItems = [
            ['name' => '時計', 'image' => 'Armani-Mens-Clock.jpg'],
            ['name' => 'レコード', 'image' => 'HDD-Hard-Disk.jpg'],
            ['name' => 'たまねぎ', 'image' => 'iLoveIMG-d.jpg'],
            ['name' => '革靴', 'image' => 'Leather-Shoes-Product-Photo.jpg'],
            ['name' => 'PC', 'image' => 'Living-Room-Laptop.jpg'],
            ['name' => '化粧品', 'image' => 'makeup.jpg'],
            ['name' => 'マイク', 'image' => 'Music-Mic-4632231.jpg'],
            ['name' => 'カバン', 'image' => 'Purse-fashion-pocket.jpg'],
            ['name' => '水筒', 'image' => 'Tumbler-souvenir.jpg'],
            ['name' => 'フィギア', 'image' => 'Waitress-with-Coffee-Grinder.jpg'],
        ];

        foreach ($dummyItems as $data) {
            Item::create([
                'name' => $data['name'],
                'image' => $data['image'],
                'price' => rand(1000, 20000), // 適当に価格をつける
                'description' => $data['name'] . 'の説明',
            ]);
        }
    }
}
