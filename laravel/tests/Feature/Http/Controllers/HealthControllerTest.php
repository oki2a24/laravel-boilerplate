<?php

namespace Tests\Feature\Http\Controllers;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class HealthControllerTest extends TestCase
{
    #[Test]
    public function 自身が生存している場合は相当するレスポンスを返すこと(): void
    {
        $response = $this->getJson('/api/health');

        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => 'pass',
            ]);
    }
}
