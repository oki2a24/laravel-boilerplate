<?php

namespace Tests\Feature\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function ログアウトに成功すること(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/logout');

        $response->assertStatus(204);
        $this->assertGuest();
    }
}
