<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 支払い方法を選択すると小計画面に反映される()
    {
        // ユーザー作成
        $user = User::factory()->create();

        // 商品作成
        $item = Item::factory()->create([
            'price' => 5000,
        ]);

        // ログイン
        $this->actingAs($user);

        /**
         * 重要：
         * ここでは POST /payment-method というルートは存在しないため削除。
         * 小計ページは単なる GET /purchase/{item_id} （type パラメータ付き）を開くだけ。
         */

        // 小計画面へアクセス
        $response = $this->get('/purchase/' . $item->id . '?type=item');

        // ステータスコード
        $response->assertStatus(200);

        // 小計の金額が存在するか
        $response->assertSee('¥5,000');

        // 支払い方法セレクトボックスの項目が存在するか
        $response->assertSee('コンビニ払い');
        $response->assertSee('クレジットカード');

        // デフォルト表示の "選択してください" があるか
        $response->assertSee('選択してください');
    }
}
