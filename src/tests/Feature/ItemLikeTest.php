<?php

namespace Tests\Feature;

use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemLikeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ユーザーがお気に入りを追加できること
     */
    public function test_user_can_add_like()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $response = $this->actingAs($user)->post('/favorites/toggle', [
            'id' => $item->id,
            'type' => 'item',
        ]);

        // 302 リダイレクトになっているので修正
        $response->assertStatus(302);
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'favoritable_id' => $item->id,
            'favoritable_type' => Item::class,
        ]);
    }

    /**
     * ユーザーがお気に入りを削除できること
     */
    public function test_user_can_remove_like()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        // まずお気に入りを作成
        $item->favorites()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->post('/favorites/toggle', [
            'id' => $item->id,
            'type' => 'item',
        ]);

        // 削除後も 302 リダイレクトされる
        $response->assertStatus(302);
        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'favoritable_id' => $item->id,
            'favoritable_type' => Item::class,
        ]);
    }

    /**
     * ゲストはログインページにリダイレクトされること
     */
    public function test_guest_is_redirected_to_login_when_toggling_like()
    {
        $item = Item::factory()->create();

        $response = $this->post('/favorites/toggle', [
            'id' => $item->id,
            'type' => 'item',
        ]);

        $response->assertRedirect('/login'); // auth ミドルウェアで自動リダイレクト
    }
}
