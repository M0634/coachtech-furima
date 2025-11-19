<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_メールアドレスが未入力の場合はエラーが表示される()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_パスワードが未入力の場合はエラーが表示される()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    /**
     * 入力情報が間違っている場合、バリデーションメッセージが表示される
     */
    public function test_登録されていない情報の場合はログインエラーが表示される()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'notfound@example.com',
            'password' => 'wrongpass',
        ]);

        // Laravel Breeze/Fortify/Jetstream は login に失敗すると
        // ->withErrors(['email' => 'These credentials do not match our records.'])
        // を返すため email にエラーが付く
        $response->assertSessionHasErrors(['email']);
        $response->assertRedirect('/login');
    }

    /**
     * 正しい情報が入力された場合、ログイン処理が実行される
     */
    public function test_正しい情報ならログインできる()
    {
        // 事前にユーザー作成
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // ログインしていることを確認
        $this->assertAuthenticatedAs($user);

        // 一般的には /dashboard へリダイレクトされる（環境による）
        $response->assertRedirect('/');
    }
}
