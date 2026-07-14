<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DarkModeTest extends TestCase
{
    #[Test]
    public function landing_page_has_three_state_init_script(): void
    {
        $response = $this->get('/');
        $content = $response->getContent();

        $response->assertOk();
        $this->assertStringContainsString('localStorage.getItem(\'dark-mode\')', $content);
        $this->assertStringContainsString("'auto'", $content);
        $this->assertStringContainsString("'dark'", $content);
        $this->assertStringContainsString("'light'", $content);
    }

    #[Test]
    public function login_page_has_three_state_init_script(): void
    {
        $response = $this->get('/login');
        $content = $response->getContent();

        $response->assertOk();
        $this->assertStringContainsString("'auto'", $content);
        $this->assertStringContainsString("'dark'", $content);
        $this->assertStringContainsString("'light'", $content);
    }

    #[Test]
    public function dashboard_page_has_three_state_init_script(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');
        $content = $response->getContent();

        $response->assertOk();
        $this->assertStringContainsString("'auto'", $content);
        $this->assertStringContainsString("'dark'", $content);
        $this->assertStringContainsString("'light'", $content);
    }

    #[Test]
    public function landing_page_has_theme_toggle_button(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('localStorage.setItem');
    }

    #[Test]
    public function guest_layout_has_theme_toggle_button(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('localStorage.setItem');
    }

    #[Test]
    public function dashboard_has_theme_toggle_buttons(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('localStorage.setItem');
    }
}
