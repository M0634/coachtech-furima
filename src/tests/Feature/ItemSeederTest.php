<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Favorite;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 商品名で部分一致検索ができる()
    {
        $user = User::factory()->create();

        // 商品作成
        Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Banana Watch']);
        Item::factory()->create(['name' => 'Laptop']);

        // 検索: "Watch"
        $response = $this->actingAs($user)->get('/?q=Watch');

        $response->assertStatus(200)
                 ->assertSee('Apple Watch', false)
                 ->assertSee('Banana Watch', false)
                 ->assertDontSee('Laptop', false);
    }

        /** @test */
    public function 検索状態がマイリストでも保持されている()
    {
        $user = User::factory()->create();

        // 商品作成
        $item1 = Item::factory()->create(['name' => 'Apple Watch']);
        $item2 = Item::factory()->create(['name' => 'Banana Watch']);
        $item3 = Item::factory()->create(['name' => 'Laptop']);

        // お気に入り登録（Apple Watch のみ）
        Favorite::factory()->create([
            'user_id' => $user->id,
            'favoritable_id' => $item1->id,
            'favoritable_type' => Item::class,
        ]);

        // 検索: "Watch" & マイリストタブ
        $response = $this->actingAs($user)->get('/?tab=mylist&q=Watch');

        $response->assertStatus(200)
                ->assertSee('Apple Watch', false)     // お気に入りのみ表示
                ->assertDontSee('Banana Watch', false) // お気に入りでないので表示されない
                ->assertDontSee('Laptop', false)
                ->assertSee('value="Watch"', false);  // 検索ボックスにキーワード保持
    }

}
