<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ItemController extends Controller
{
    // 商品一覧（トップページ）
    public function index()
    {
        // resources/views/home/index.blade.php
        return view('home.index');
    }

    // 商品一覧（マイリスト）
    public function mylist()
    {
        // resources/views/home/mylist.blade.php
        return view('home.mylist');
    }

    // 商品詳細
    public function show($item_id)
    {
        // resources/views/item/detail.blade.php
        return view('item.detail', compact('item_id'));
    }
}
