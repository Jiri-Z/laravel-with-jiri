<?php

namespace Tests\Feature;

use Tests\TestCase;

class SmokeGuestTest extends TestCase
{
    public function test_guest_redirected_to_login(): void
    {
        $this->get('/courses')->assertRedirect('/login');
    }

    public function test_landing_and_legal_pages(): void
    {
        $this->get('/')->assertOk()->assertSee('Laravel With Jiri');
        $this->get('/terms')->assertOk()->assertSee('Terms of Service');
        $this->get('/privacy')->assertOk()->assertSee('Privacy Policy');
    }
}
