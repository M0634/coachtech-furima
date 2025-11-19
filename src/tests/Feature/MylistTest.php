<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use App\Models\Purchase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function いいねした商品だけが表示される()
    {
        $user = User::factory()->create();

        // 他人の商品
        $item1 = Item::factory()->create(['name' => '商品A']);
        $item2 = Item::factory()->create(['name' => '商品B']);

        // $item1 をお気に入りにする
        Favorite::factory()->create([
            'user_id' => $user->id,
            'favoritable_id' => $item1->id,
            'favoritable_type' => Item::class,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200)
                 ->assertSee('商品A', false)
                 ->assertDontSee('商品B', false);
    }

    /** @test */
    public function 購入済み商品には_SOLD_が表示される()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => '購入商品']);

        // お気に入りに登録
        Favorite::factory()->create([
            'user_id' => $user->id,
            'favoritable_id' => $item->id,
            'favoritable_type' => Item::class,
        ]);

        // 購入済みにする
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'status' => '購入済み',
            'total_price' => 1000,
        ]);

        $response = $this->actingAs($user)->get('/?tab=mylist');

        $response->assertStatus(200)
                 ->assertSee('SOLD', false);
    }

    /** @test */
    public function 未認証の場合は何も表示されない()
    {
        $item = Item::factory()->create(['name' => '商品A']);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200)
                 ->assertDontSee('商品A', false);
    }
}
