<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function 名前が未入力の場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // DBにユーザーが作られていないことを確認
        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);

        // バリデーションエラーで元の画面にリダイレクト
        $response->assertSessionHasErrors(['name']);

        // リダイレクト先を柔軟に確認
        $response->assertStatus(302);
    }

    /** @test */
    public function メールアドレスが未入力の場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseMissing('users', ['name' => 'テストユーザー']);
        $response->assertSessionHasErrors(['email']);
        $response->assertStatus(302);
    }

    /** @test */
    public function パスワードが未入力の場合はエラーメッセージが表示される()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => '',
        ]);

        $this->assertDatabaseMissing('users', ['email' => 'test@example.com']);
        $response->assertSessionHasErrors(['password']);
        $response->assertStatus(302);
    }

    /** @test */
    public function 正しい入力なら登録されメール認証画面にリダイレクトされる()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // DBにユーザーが登録されていることを確認
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        // メール認証有効なので /email/verify にリダイレクト
        $response->assertRedirect('/email/verify');
    }
}
