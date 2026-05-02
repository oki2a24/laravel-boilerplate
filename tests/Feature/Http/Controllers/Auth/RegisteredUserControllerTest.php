<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function ユーザーを登録できること(): void
    {
        Event::fake();

        $response = $this->postJson('/api/register', [
            'name' => 'ユーザー登録テスト',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(204);

        // イベントがディスパッチされたことをアサート
        Event::assertDispatched(Registered::class);
    }
}
