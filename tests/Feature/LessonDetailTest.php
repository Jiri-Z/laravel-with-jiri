<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $response = $this->actingAs($user)->get("/courses/{$course->slug}/lessons/{$lesson->slug}");

        $response->assertOk();
        $response->assertSee('No steps available yet');
    }
}
