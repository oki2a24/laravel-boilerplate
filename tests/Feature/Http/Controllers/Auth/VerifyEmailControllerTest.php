<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class VerifyEmailControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function メールアドレスが検証済みとしてマークされること(): void
    {
        $user = User::factory()->unverified()->create();

        Event::fake();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->getJson($url);

        // イベント確認
        Event::assertDispatched(Verified::class);
        // メールアドレスの検証状態確認
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // レスポンス確認
        $response->assertNoContent();
    }

    #[Test]
    public function メールアドレスがすでに検証済みの場合は204を返すこと(): void
    {
        $user = User::factory()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->getJson($url);

        // レスポンス確認
        $response->assertNoContent();
    }

    #[Test]
    public function idが一致しない場合は検証済みとしてマークされないこと(): void
    {
        $user = User::factory()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id + 1,
                'hash' => sha1($user->email),
            ]
        );

        $response = $this->actingAs($user)->getJson($url);

        // レスポンス確認
        $response->assertForbidden();
    }

    #[Test]
    public function メールアドレスが一致しない、つまりハッシュが一致しない、場合は検証済みとしてマークされないこと(): void
    {
        $user = User::factory()->create();

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email.'comcomcom'),
            ]
        );

        $response = $this->actingAs($user)->getJson($url);

        // レスポンス確認
        $response->assertForbidden();
    }
}
