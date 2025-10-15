<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestImageController extends Controller
{
    public function index()
    {
        // public/images 配下の画像ファイル名を直接配列で指定
        $images = [
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

        return view('test_images', compact('images'));
    }
}
