<?php

namespace Tests\Feature\Http\Controllers\Auth\NewPasswordController;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function パスワードがリセットできること(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $token = Password::createToken($user);
        $newPassword = 'new-password!!!!!';

        $response = $this->postJson('/api/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);

        // レスポンス確認
        $response
            ->assertOk()
            ->assertJson(['status' => trans(Password::PASSWORD_RESET)]);
        // DB 確認
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));
        // イベント確認
        Event::assertDispatched(PasswordReset::class, function ($event) use ($user) {
            return $event->user->id === $user->id;
        });
    }

    /** @test */
    public function 不正なトークンによりエラーとなること(): void
    {
        Event::fake();

        $oldPassword = 'old-password@@@@@';
        $user = User::factory()->create([
            'password' => Hash::make($oldPassword),
        ]);

        $response = $this->postJson('/api/reset-password', [
            'token' => 'invalid-token',
            'email' => $user->email,
            'password' => 'new-password!!!!!',
            'password_confirmation' => 'new-password!!!!!',
        ]);

        // レスポンス確認
        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
        // DB 確認
        $this->assertTrue(Hash::check($oldPassword, $user->fresh()->password));
        // イベント確認
        Event::assertNotDispatched(PasswordReset::class);
    }
}
