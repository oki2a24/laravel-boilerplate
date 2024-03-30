<?php

namespace Tests\Feature\Http\Controllers\Auth\EmailVerificationNotificationController;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class StoreTest extends TestCase
{
    /** @test */
    public function メール確認のメールを送信すること(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/email/verification-notification');

        // レスポンスを確認
        $response->assertAccepted();

        // メール送信を確認
        Notification::assertCount(1);
        Notification::assertSentTo(
            [$user], VerifyEmail::class
        );
    }

    /** @test */
    public function メール確認済みの場合はメールを送信しないこと()
    {
        Notification::fake();

        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/email/verification-notification');

        // レスポンスを確認
        $response->assertNoContent();

        // メール送信を確認
        Notification::assertNothingSent();
        Notification::assertNotSentTo(
            [$user], VerifyEmail::class
        );
    }
}
