<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomeRouteTest extends TestCase
{
    public function test_home_page_is_accessible(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
