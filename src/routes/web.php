<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AddressController;

/*
|--------------------------------------------------------------------------
| メール認証関連
|--------------------------------------------------------------------------
*/

// 認証未完了ユーザー向け画面（メール送信済みの案内）
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証メール再送信処理
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証メールを再送信しました。');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// メール内リンクからアクセス（正式なメール認証）
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // 初回ログインフラグ更新
    $user = $request->user();
    if ($user && ! $user->first_login_verified) {
        $user->first_login_verified = true;
        $user->save();
    }

    return redirect('/mypage/profile')->with('message', 'メール認証が完了しました！');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| 商品関連
|--------------------------------------------------------------------------
*/

// トップ（商品一覧）
Route::get('/', [HomeController::class, 'index'])->name('home');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');

// =============================
// 購入関連（要ログイン）
// =============================
Route::middleware('auth')->group(function () {
    // 購入確認画面
    Route::get('/purchase/{item}', [PurchaseController::class, 'show'])->name('purchase.show');

    // 購入処理（直接DB保存する場合）
    Route::post('/purchase/{item}', [PurchaseController::class, 'store'])->name('purchase.store');

    // Stripe決済開始
    Route::post('/purchase/{item}/checkout', [PurchaseController::class, 'stripeCheckout'])->name('purchase.checkout');

    // Stripe支払い成功後（Stripe success_url）
    Route::get('/purchase/{item}/success', [PurchaseController::class, 'success'])->name('purchase.success');

    // 配送先住所変更
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('purchase.address.edit');
    Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');
});

/*
|--------------------------------------------------------------------------
| 出品関連
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/sell', [SellController::class, 'index'])->name('sell');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});

/*
|--------------------------------------------------------------------------
| 認証関連（パスワードリセット無効）
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

/*
|--------------------------------------------------------------------------
| マイページ関連
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    // プロフィール編集
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    // 購入／販売履歴
    Route::get('/mypage/buy', [MypageController::class, 'purchasedItems']);
    Route::get('/mypage/sell', [MypageController::class, 'soldItems']);

    // コメント投稿
    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');

    // お気に入り登録／削除
    Route::post('/favorite/{item_id}', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorite/{item_id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});


