<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class PurchaseController extends Controller
{
    /**
     * 購入確認画面（GET /purchase/{id}?type=item|product）
     */
    public function show(Request $request, $id)
    {
        $type = $request->query('type', 'item');

        if ($type === 'item') {
            $productOrItem = Item::with(['comments.user', 'favorites'])->findOrFail($id);
        } elseif ($type === 'product') {
            $productOrItem = Product::with(['comments.user', 'favorites'])->findOrFail($id);
        } else {
            abort(404);
        }

        $user = auth()->user()->fresh(['profile', 'address']);
        $profile = $user->address ?? $user->profile ?? null;

        return view('purchase.buy', compact('productOrItem', 'profile', 'type'));
    }

    /**
     * Stripe決済開始（POST /purchase/{id}?type=item|product）
     */
    public function store(Request $request, $id)
    {
        $type = $request->input('type', 'item');
        $paymentMethod = $request->input('payment_method');
        $user = auth()->user();

        if (! in_array($paymentMethod, ['コンビニ払い', 'クレジットカード'])) {
            return back()->with('error', '選択された支払い方法は対応していません。');
        }

        // 対象商品取得
        if ($type === 'item') {
            $productOrItem = Item::findOrFail($id);
        } elseif ($type === 'product') {
            $productOrItem = Product::findOrFail($id);
        } else {
            abort(404);
        }

        // Stripe セット
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $unitAmount = (int) $productOrItem->price;

        $session = StripeSession::create([
            'payment_method_types' => [$paymentMethod === 'コンビニ払い' ? 'konbini' : 'card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $productOrItem->name],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $id, 'type' => $type]), // ←修正
            'cancel_url' => route('purchase.show', ['item_id' => $id, 'type' => $type]),     // ←修正
        ]);

        return redirect($session->url);
    }

    /**
     * 決済成功後の処理
     */
    public function success(Request $request, $item_id)
    {
        $type = $request->query('type', 'item');
        $user = auth()->user()->refresh();  // 最新情報に更新
        $profile = $user->profile;

        // 商品の取得（items または products）
        $productOrItem = null;
        if ($type === 'item') {
            $productOrItem = Item::find($item_id);
        } elseif ($type === 'product') {
            $productOrItem = Product::find($item_id);
        }

        // どちらにも存在しなければ404
        if (! $productOrItem) {
            abort(404, '商品が見つかりません。');
        }

        // 🟢 購入記録の作成処理
        $purchase = new Purchase;
        $purchase->user_id = $user->id;

        // ✅ ←ここで条件分岐して保存
        if ($item = Item::find($item_id)) {
            $purchase->item_id = $item->id;
        } elseif ($product = Product::find($item_id)) {
            $purchase->product_id = $product->id;
        }

        // 共通項目
        $purchase->postal_code = $profile->postal_code ?? '';
        $purchase->address = $profile->address ?? '';
        $purchase->building = $profile->building ?? '';
        $purchase->quantity = 1;
        $purchase->total_price = $productOrItem->price;
        $purchase->status = '購入済み';
        $purchase->save();

        return redirect('/')->with('success', '購入が完了しました！');
    }

    public function stripeCheckout(Request $request, $item_id)
    {
        $type = $request->input('type', 'item');
        $paymentMethod = $request->input('payment_method');
        $user = auth()->user();
        $profile = $user->profile()->first();

        if (! in_array($paymentMethod, ['コンビニ払い', 'クレジットカード'])) {
            return back()->with('error', '選択された支払い方法は対応していません。');
        }

        if ($type === 'item') {
            $productOrItem = Item::findOrFail($item_id);
        } elseif ($type === 'product') {
            $productOrItem = Product::findOrFail($item_id);
        } else {
            abort(404);
        }

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $unitAmount = (int) $productOrItem->price;

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => [$paymentMethod === 'コンビニ払い' ? 'konbini' : 'card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $productOrItem->name],
                    'unit_amount' => $unitAmount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item_id, 'type' => $type]),
            'cancel_url' => route('purchase.show', ['item_id' => $item_id, 'type' => $type]),
        ]);

        return redirect($session->url);
    }
}
