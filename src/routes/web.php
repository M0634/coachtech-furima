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
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\TestImageController;


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
    return redirect('/mypage/profile')->with('message', 'メール認証が完了しました！');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| 認証後ページ
|--------------------------------------------------------------------------
*/

// Route::get('/profile', [ProfileController::class, 'index'])
//     ->middleware(['auth', 'verified'])
//     ->name('profile');

/*
|--------------------------------------------------------------------------
| 商品関連
|--------------------------------------------------------------------------
*/

// トップ（商品一覧）
Route::get('/', [HomeController::class, 'index'])->name('home');

// マイリスト（HomeControllerでリダイレクト処理でもOK）
Route::get('/mylist', [HomeController::class, 'index'])->name('mylist');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('items.show');


// 商品購入
Route::get('/purchase/{item_id}', [PurchaseController::class, 'show']);
Route::post('/purchase/{item_id}', [PurchaseController::class, 'store']);

// 送付先住所変更
Route::get('/purchase/address/{item_id}', [PurchaseController::class, 'editAddress']);
Route::post('/purchase/address/{item_id}', [PurchaseController::class, 'updateAddress']);

// 商品出品
Route::get('/sell', [SellController::class, 'index'])->name('sell');
Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

/*
|--------------------------------------------------------------------------
| 認証関連（パスワードリセット無効）
|--------------------------------------------------------------------------
*/

// Auth::routes(); // ←コメントアウトして無効化

// 会員登録
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.post');

// ログイン／ログアウト
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| マイページ関連
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage');
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('mypage.profile.edit');
    Route::put('/mypage/profile', [ProfileController::class, 'update'])->name('mypage.profile.update');
    Route::get('/mypage/buy', [MypageController::class, 'purchasedItems']);
    Route::get('/mypage/sell', [MypageController::class, 'soldItems']);

    Route::post('/items/{item}/comments', [CommentController::class, 'store'])->name('comments.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/favorite/{item_id}', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/favorite/{item_id}', [FavoriteController::class, 'destroy'])->name('favorite.destroy');
});

Route::get('/test-images', [TestImageController::class, 'index']);
