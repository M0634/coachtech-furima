<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| メール認証関連
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '認証メールを再送信しました。');
    })->middleware('throttle:6,1')->name('verification.send');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // テスト環境でも必ずユーザーを取得
    $user = $request->user() ?? \App\Models\User::find($request->route('id'));

    if ($user && ! $user->first_login_verified) {
        $user->first_login_verified = true;
        $user->save();
    }

    return redirect('/mypage/profile')->with('message', 'メール認証が完了しました！');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

});

/*
|--------------------------------------------------------------------------
| トップ / 商品一覧
|--------------------------------------------------------------------------
*/
Route::get('/', [ItemController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| 商品詳細（Item / Product 共通）
|--------------------------------------------------------------------------
*/
Route::get('/{type}/{id}', [ItemController::class, 'showItemOrProduct'])
    ->where('type', 'item|product')
    ->name('item.show');

// 商品詳細（item）
Route::get('/items/{id}', [ItemController::class, 'show'])->name('items.show');

// 商品詳細（product）
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| お気に入りトグル（Item・Product共通）
|--------------------------------------------------------------------------
*/
Route::post('/favorites/toggle', [ItemController::class, 'toggleFavorite'])
    ->middleware('auth')   // ← ここでログイン必須にする
    ->name('favorites.toggle');

/*
|--------------------------------------------------------------------------
| コメント投稿（Item・Product共通）
|--------------------------------------------------------------------------
*/
Route::post('/{type}/{id}/comments', [ItemController::class, 'storeComment'])
    ->where('type', 'item|product')
    ->name('comments.store');
/*
|--------------------------------------------------------------------------
| 認証・登録関連
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

/*
|--------------------------------------------------------------------------
| 購入関連（ログイン必須）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('purchase')->group(function () {

    // 購入確認画面
    Route::get('{item_id}', [PurchaseController::class, 'show'])->name('purchase.show');

    // Stripe決済開始（checkout用）
    Route::post('{item_id}/checkout', [PurchaseController::class, 'stripeCheckout'])->name('purchase.checkout');

    // 決済成功後
    Route::get('{item_id}/success', [PurchaseController::class, 'success'])->name('purchase.success');

    // 住所編集・更新
    Route::get('address/{item_id}', [AddressController::class, 'edit'])->name('purchase.address.edit');
    Route::post('address/{item_id}', [AddressController::class, 'update'])->name('purchase.address.update');

});

/*
|--------------------------------------------------------------------------
| 出品関連（ログイン必須）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/sell', [SellController::class, 'create'])->name('sell');

    // 出品データ保存
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');
});

/*
|--------------------------------------------------------------------------
| マイページ関連（ログイン必須）
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');

    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');

    Route::get('/mypage/buy', [MypageController::class, 'purchasedItems'])->name('mypage.buy');
    Route::get('/mypage/sell', [MypageController::class, 'soldItems'])->name('mypage.sell');
});
