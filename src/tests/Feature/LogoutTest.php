<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウトできる()
    {
        // 1. ユーザーを作成してログイン状態にする
        $user = User::factory()->create();
        $this->actingAs($user);

        // 実際にログアウトリクエストを送る
        $response = $this->post('/logout');

        // ログアウト後は未ログイン状態になる
        $this->assertGuest();

        // リダイレクト先は Laravel 標準だと "/"
        $response->assertRedirect('/');
    }
}
