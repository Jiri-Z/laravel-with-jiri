<?php

namespace Tests\Feature;

use App\Livewire\CourseList;
use App\Models\Course;
use App\Models\StepCompletion;
use App\Models\User;
use Livewire\Livewire;
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

    public function test_authenticated_user_sees_progress_indicator(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep();

        $this->actingAs($user)->get('/courses')->assertSee('0%');

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        $this->actingAs($user)->get('/courses')->assertSee('100%');
    }

    public function test_zero_percent_when_course_has_steps_but_no_completions(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep();

        $this->actingAs($user)->get('/courses')->assertSee('0%');
    }

    public function test_published_course_with_no_lessons_shows_progress(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'Empty Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $this->actingAs($user)->get('/courses')
            ->assertOk()
            ->assertSee('Empty Course')
            ->assertSee('0%');
    }

    public function test_unenrolled_user_sees_enroll_button(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'Sample Course']);

        $this->actingAs($user)->get('/courses')
            ->assertSee('Enroll');

        Livewire::actingAs($user)
            ->test(CourseList::class)
            ->assertSee('Enroll');
    }

    public function test_enrolled_user_does_not_see_enroll_button(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'Sample Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $this->actingAs($user)->get('/courses')
            ->assertDontSee('Enroll');

        Livewire::actingAs($user)
            ->test(CourseList::class)
            ->assertDontSee('Enroll');
    }
}
