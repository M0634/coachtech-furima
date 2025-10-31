<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面（GET /purchase/{item}）
     */
    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        $profile = auth()->user()->profile;

        return view('purchase.buy', compact('item', 'profile'));
    }

    /**
     * Stripe決済開始（POST /purchase/{item}）
     */
    public function store(Request $request, $item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        $paymentMethod = $request->input('payment_method');

        // 支払い方法チェック
        if (!in_array($paymentMethod, ['コンビニ払い', 'クレジットカード'])) {
            return back()->with('error', '選択された支払い方法は対応していません。');
        }

        // ✅ Stripeシークレットキーを設定
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // ✅ Stripe決済セッション作成
        $session = StripeSession::create([
            'payment_method_types' => [$paymentMethod === 'コンビニ払い' ? 'konbini' : 'card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    // ✅ 日本円は *100 不要！
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', $item->id),
            'cancel_url' => route('purchase.show', $item->id),
        ]);

        // ✅ Stripeの決済ページにリダイレクト
        return redirect($session->url);
    }

    /**
     * 決済成功後の処理
     */
    public function success($item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        $profile = $user->profile;

        // ✅ 購入記録を作成
        Purchase::create([
            'user_id'     => $user->id,
            'item_id'     => $item->id,
            'postal_code' => $profile->postal_code ?? '',
            'address'     => $profile->address ?? '',
            'building'    => $profile->building ?? '',
            'quantity'    => 1,
            'total_price' => $item->price,
            'status'      => '購入済み',
        ]);

        // ✅ 商品ステータス更新
        $item->update(['status' => 'sold']);

        return redirect('/')->with('success', '購入が完了しました！');
    }

    public function stripeCheckout(Request $request, $item_id)
    {
        $user = auth()->user();
        $item = Item::findOrFail($item_id);
        $paymentMethod = $request->input('payment_method');

        // 支払い方法チェック
        if (!in_array($paymentMethod, ['コンビニ払い', 'クレジットカード'])) {
            return back()->with('error', '選択された支払い方法は対応していません。');
        }

        // ✅ Stripeシークレットキーを設定
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        // ✅ 金額（日本円なので100倍しない！）
        $unitAmount = (int) $item->price;

        // ✅ Stripeセッション作成
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$paymentMethod === 'コンビニ払い' ? 'konbini' : 'card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $item->name],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', $item->id),
            'cancel_url' => route('purchase.show', $item->id),
        ]);

        // ✅ Stripe決済ページにリダイレクト
        return redirect($session->url);
    }
}
