<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisteredUserControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ユーザーを登録できること(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'ユーザー登録テスト',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(204);
    }
}
