<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class AddressUpdateTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザー情報取得ができる()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('profile_test.png', 10);

        $user = User::factory()->create();

        $user->profile()->create([
            'name' => 'テストユーザー',
            'image' => $file->store('profile', 'public'),
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
    }

    /** @test */
    public function ユーザー情報変更画面に初期値が反映される()
    {
        $user = User::factory()->create();

        $user->profile()->create([
            'name' => 'テストユーザー',
            'postal_code' => '987-6543',
            'address' => '大阪府大阪市テスト2-2',
            'building' => 'サンプルマンション202',
            'image' => 'profile_test.png',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('value="テストユーザー"', false);
        $response->assertSee('value="987-6543"', false);
        $response->assertSee('value="大阪府大阪市テスト2-2"', false);
        $response->assertSee('value="サンプルマンション202"', false);
    }
}
