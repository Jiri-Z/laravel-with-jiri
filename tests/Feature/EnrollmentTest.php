<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    public function test_guest_cannot_enroll(): void
    {
        $course = Course::factory()->published()->create();

        $this->post(route('courses.enroll', $course))->assertRedirect('/login');
    }

    public function test_user_can_enroll_in_published_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        $this->actingAs($user)
            ->post(route('courses.enroll', $course))
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('course_enrollments', [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    public function test_user_cannot_enroll_in_unpublished_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);

        $this->actingAs($user)
            ->post(route('courses.enroll', $course))
            ->assertNotFound();
    }

    public function test_enrolling_in_nonexistent_course_returns_404(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post('/enroll/99999')
            ->assertNotFound();
    }

    public function test_enrolling_twice_is_idempotent(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        $this->actingAs($user)->post(route('courses.enroll', $course));
        $this->actingAs($user)->post(route('courses.enroll', $course));

        $this->assertDatabaseCount('course_enrollments', 1);
    }

    public function test_unenrolled_user_cannot_view_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        $this->actingAs($user)
            ->get(route('courses.show', $course))
            ->assertRedirect(route('courses.index'));
    }

    public function test_unenrolled_user_cannot_view_lesson(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $this->actingAs($user)
            ->get(route('lessons.show', [$course->slug, $lesson->slug]))
            ->assertRedirect(route('courses.index'));
    }

    public function test_unenrolled_user_cannot_view_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user)
            ->get(route('steps.show', [$course->slug, $lesson->slug, $step->id]))
            ->assertRedirect(route('courses.index'));
    }

    public function test_enrolled_user_can_view_course(): void
    {
        [$user, $course] = $this->enrolledUser();

        $this->actingAs($user)
            ->get(route('courses.show', $course))
            ->assertOk();
    }
}
