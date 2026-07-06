<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    public function test_guest_can_view_landing_page(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_landing_page_shows_published_courses(): void
    {
        Course::factory()->published()->create(['title' => 'Laravel Basics', 'order' => 1]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Laravel Basics');
    }

    public function test_landing_page_hides_unpublished_courses(): void
    {
        Course::factory()->create(['title' => 'Draft Course', 'published' => false]);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('Draft Course');
    }

    public function test_landing_page_shows_registration_link(): void
    {
        $this->get('/')->assertSee(__('landing.hero_cta_start'));
    }

    public function test_authenticated_user_sees_courses_link(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/')
            ->assertOk()
            ->assertSee(__('Dashboard'));
    }

    public function test_landing_page_shows_empty_state_when_no_courses(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(__('landing.courses_empty'));
    }
}
