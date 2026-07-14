<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeErrorPagesTest extends TestCase
{
    public function test_known_good_page_loads(): void
    {
        $this->get('/')->assertSuccessful();
    }

    public function test_non_existent_route_returns_404(): void
    {
        $this->get('/this-route-does-not-exist-12345')->assertNotFound();
    }
}
