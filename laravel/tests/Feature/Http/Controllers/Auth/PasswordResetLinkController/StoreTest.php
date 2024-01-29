<?php

namespace Tests\Feature\Http\Controllers\Auth\PasswordResetLinkController;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function パスワードリセットリクエストを送信すること(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this->postJson('/api/forgot-password', ['email' => $user->email]);

        $response->assertOk();
        Notification::assertSentTo($user, ResetPassword::class);
    }

    /** @test */
    public function メールアドレスが存在しない場合はエラーを返すこと(): void
    {
        $response = $this->postJson('/api/forgot-password', ['email' => 'test@example.com']);

        $response->assertStatus(422);
    }
}
