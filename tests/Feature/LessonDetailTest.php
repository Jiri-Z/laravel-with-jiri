<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Tests\TestCase;

class LessonDetailTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $this->get("/courses/{$course->slug}/lessons/{$lesson->slug}")->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_lesson_steps(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'My Course']);
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create([
            'course_id' => $course->id,
            'title' => 'My Lesson',
        ]);
        Step::factory()->count(3)->sequence(
            ['order' => 1],
            ['order' => 2],
            ['order' => 3],
        )->create(['lesson_id' => $lesson->id]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");

        $response->assertOk();
        $response->assertSee('My Lesson');
    }

    public function test_steps_are_ordered_by_order_column(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $second = Step::factory()->create(['lesson_id' => $lesson->id, 'title' => 'Second Step', 'order' => 2]);
        $first = Step::factory()->create(['lesson_id' => $lesson->id, 'title' => 'First Step', 'order' => 1]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");

        $response->assertSeeInOrder(['First Step', 'Second Step']);
    }

    public function test_draft_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->create([
            'course_id' => $course->id,
            'published' => false,
        ]);

        $this->actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}")->assertNotFound();
    }

    public function test_empty_state_when_lesson_has_no_steps(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");

        $response->assertOk();
        $response->assertSee('No steps available yet');
    }

    public function test_lesson_detail_shows_step_completion_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'My Lesson']);
        $step = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1, 'title' => 'The Step']);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
        $response->assertOk();
        $response->assertSee('The Step');
        $response->assertSee('Completed');
    }

    public function test_lesson_from_wrong_course_returns_404(): void
    {
        $user = User::factory()->create();
        $courseA = Course::factory()->published()->create();
        $courseB = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $courseA->id]);

        $this->actingAs($user)
            ->get("/courses/{$courseB->slug}/lessons/{$lesson->slug}")
            ->assertNotFound();
    }

    public function test_draft_course_hides_published_lesson(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
            ->assertNotFound();
    }

    public function test_incomplete_step_does_not_show_completed_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1, 'title' => 'Pending Step']);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
        $response->assertOk();
        $response->assertSee('Pending Step');
        $response->assertDontSee('Completed');
    }

    public function test_locked_step_shows_lock_icon(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'title' => 'Locked Step', 'order' => 2]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}");
        $response->assertOk();
        $response->assertSee('Locked Step');
        $response->assertSee('Locked');
    }
}
