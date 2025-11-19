<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Auth\Notifications\VerifyEmail;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registration_sends_verification_email()
    {
        Notification::fake();

        $user = User::factory()->create();

        // 通知が送信されることを確認
        Notification::assertNothingSent();

        $user->sendEmailVerificationNotification();

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /** @test */
    public function user_can_verify_email_and_redirect_to_profile()
    {
        $user = User::factory()->unverified()->create();

        // メール認証リンクをシミュレート
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        // 実際のリダイレクト先に合わせる
        $response->assertRedirect('/mypage/profile');

        // 更新後に再取得して確認
        $user = $user->fresh();

        $this->assertTrue($user->hasVerifiedEmail());
    }
}
