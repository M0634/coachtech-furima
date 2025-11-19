<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Item;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** ログイン済みユーザーはコメントを送信できる */
    public function test_logged_in_user_can_send_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', [
            'type' => 'item',
            'id'   => $item->id,
        ]), [
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect('/'); // コントローラー仕様に合わせる

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
            'commentable_id' => $item->id,
            'commentable_type' => Item::class,
        ]);

        $this->assertEquals(1, Comment::count());
    }

    /** ゲストユーザーはコメントできない */
    public function test_guest_user_cannot_send_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post(route('comments.store', [
            'type' => 'item',
            'id'   => $item->id,
        ]), [
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'content' => 'テストコメント',
        ]);
    }

    /** 未入力 → バリデーションエラー */
    public function test_comment_validation_error_when_empty()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post(route('comments.store', [
            'type' => 'item',
            'id'   => $item->id,
        ]), [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertDatabaseCount('comments', 0);
    }

    /** 255字以上 → バリデーションエラー */
    public function test_comment_validation_error_when_over_255_characters()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $long = str_repeat('あ', 256);

        $response = $this->post(route('comments.store', [
            'type' => 'item',
            'id'   => $item->id,
        ]), [
            'content' => $long,
        ]);

        $response->assertSessionHasErrors('content');
        $this->assertDatabaseCount('comments', 0);
    }
}
