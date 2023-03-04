<?php

namespace Tests\Feature\Http\Controllers;

use Tests\TestCase;

class HealthControllerTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function 自身が生存している場合は相当するレスポンスを返すこと(): void
    {
        $response = $this->getJson('/api/health');

        $response
        ->assertStatus(200)
        ->assertExactJson([
            'status' => 'pass',
        ]);
    }

    /**
     * @test
     *
     * @return void
     */
    public function 依存しているDBが生存している場合は相当するレスポンスを返すこと(): void
    {
        $response = $this->getJson('/api/health/deep');

        $response
        ->assertStatus(200)
        ->assertExactJson([
            'status' => 'pass',
            'message' => 'success to connect db',
        ]);
    }
}
