<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Tests\TestCase;

class CourseBrowseTest extends TestCase
{
    public function test_guest_is_redirected_to_login_when_browsing_courses(): void
    {
        $this->get('/courses')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_published_courses(): void
    {
        $user = User::factory()->create();
        $published = Course::factory()->published()->create(['title' => 'Visible Course']);
        $draft = Course::factory()->create(['title' => 'Hidden Draft']);

        $response = $this->actingAs($user)->get('/courses');

        $response->assertOk();
        $response->assertSee('Visible Course');
        $response->assertDontSee('Hidden Draft');
    }

    public function test_courses_are_ordered_by_the_order_column(): void
    {
        $user = User::factory()->create();
        Course::factory()->published()->create(['title' => 'Second', 'order' => 2]);
        Course::factory()->published()->create(['title' => 'First', 'order' => 1]);

        $response = $this->actingAs($user)->get('/courses');

        $response->assertSeeInOrder(['First', 'Second']);
    }

    public function test_empty_state_when_no_published_courses(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/courses');

        $response->assertOk();
        $response->assertSee('No courses available yet');
    }
}
