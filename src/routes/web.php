<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// 商品一覧（トップ）
Route::get('/', [ItemController::class, 'index']);

// マイリスト
Route::get('/mylist', [ItemController::class, 'mylist']);

// 会員登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
Route::post('/register', [RegisterController::class, 'register']);

// ログイン
Route::get('/login', [LoginController::class, 'showLoginForm']);
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show']);

// 商品購入
Route::get('/purchase/{item_id}', [PurchaseController::class, 'show']);
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);

// 送付先住所変更
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress']);
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);

// 商品出品
Route::get('/sell', [SellController::class, 'create']);
Route::post('/sell', [SellController::class, 'store']);

// プロフィール
Route::get('/mypage', [MypageController::class, 'index']);

// プロフィール編集
Route::get('/mypage/profile', [MypageController::class, 'edit']);
Route::post('/mypage/profile', [MypageController::class, 'update']);

// プロフィール購入一覧
Route::get('/mypage/buy', [MypageController::class, 'purchasedItems']);

// プロフィール出品一覧
Route::get('/mypage/sell', [MypageController::class, 'soldItems']);
