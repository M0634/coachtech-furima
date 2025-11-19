<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 購入した商品がプロフィールの購入一覧に表示される()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create();

        // 購入履歴作成
        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'quantity' => 1,
            'total_price' => $item->price,
            'status' => '購入済み',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building' => 'テストビル',
        ]);

        // ユーザーでログイン
        $this->actingAs($user);

        // マイページ index に ?page=buy を付けて購入タブを指定
        $response = $this->get(route('mypage', ['page' => 'buy']));

        $response->assertStatus(200);

        // 商品名が表示されていることを確認
        $response->assertSee($item->name);
    }
}
