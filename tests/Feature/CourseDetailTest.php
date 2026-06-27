<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Tests\TestCase;

class CourseDetailTest extends TestCase
{
    public function test_guest_is_redirected_to_login_when_viewing_course(): void
    {
        $course = Course::factory()->create();

        $this->get("/courses/{$course->slug}")->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_course_lessons(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'My Course']);
        $lessons = Lesson::factory()->count(3)->published()->create([
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");

        $response->assertOk();
        $response->assertSee('My Course');
        foreach ($lessons as $lesson) {
            $response->assertSee($lesson->title);
        }
    }

    public function test_draft_course_is_not_visible(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);

        $this->actingAs($user)->get("/courses/{$course->slug}")->assertNotFound();
    }

    public function test_lessons_are_ordered_by_order_column(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $second = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Second', 'order' => 2]);
        $first = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'First', 'order' => 1]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");

        $response->assertSeeInOrder(['First', 'Second']);
    }

    public function test_empty_state_when_course_has_no_published_lessons(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");

        $response->assertOk();
        $response->assertSee('No lessons available yet');
    }

    public function test_course_detail_shows_progress_bar(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step1->id,
        ]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");
        $response->assertOk();
        $response->assertSee('50%');
    }

    public function test_course_detail_shows_lesson_completion_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Finished Lesson']);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");
        $response->assertOk();
        $response->assertSee('Finished Lesson');
        $response->assertSee('Lesson complete');
    }

    public function test_unpublished_lessons_are_hidden(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create(['title' => 'My Course']);
        Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Visible Lesson']);
        Lesson::factory()->create(['course_id' => $course->id, 'title' => 'Hidden Lesson', 'published' => false]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");
        $response->assertOk();
        $response->assertSee('Visible Lesson');
        $response->assertDontSee('Hidden Lesson');
    }

    public function test_nonexistent_course_slug_returns_404(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/courses/nonexistent-slug')->assertNotFound();
    }

    public function test_course_detail_shows_no_lessons_for_db_empty(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");
        $response->assertOk();
        $response->assertSee('No lessons available yet');
    }

    public function test_incomplete_lesson_does_not_show_completion_badge(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id, 'title' => 'Unfinished']);
        Step::factory()->create(['lesson_id' => $lesson->id]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}");
        $response->assertOk();
        $response->assertSee('Unfinished');
        $response->assertDontSee('Lesson complete');
    }
}
