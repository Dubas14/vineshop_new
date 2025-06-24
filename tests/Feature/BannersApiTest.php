<?php

namespace Tests\Feature;

use App\Models\Banner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannersApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_banners_endpoint_returns_banners(): void
    {
        Banner::factory()->count(2)->create();

        $response = $this->getJson('/api/banners');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }
}
