<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 全商品を取得できる()
    {
        // 表示される商品を作成
        Item::factory()->create(['name' => '腕時計']);

        $response = $this->get('/');

        $response->assertStatus(200)
                 ->assertViewIs('home.index')
                 ->assertSee('腕時計', false); // Blade の中に腕時計が表示されていればOK
    }

    /** @test */
    public function 購入済み商品には_sold_が表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'テスト商品']);

        // BladeのSold判定に合わせて購入データを作成
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => '購入済み',
            'total_price' => 1000,
        ]);

        // 購入者としてログイン
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200)
                 ->assertViewIs('home.index')
                 ->assertSee('SOLD', false); // Bladeの大文字SOLDと一致
    }

    /** @test */
    public function 自分が出品した商品は表示されない()
    {
        $user = User::factory()->create();

        // 自分の商品
        Item::factory()->create([
            'user_id' => $user->id,
            'name' => '自分の商品'
        ]);

        // 他人の商品（表示されるべき）
        Item::factory()->create([
            'name' => '他人の商品'
        ]);

        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品', false);
        $response->assertSee('他人の商品', false);
    }
}
