<?php

namespace Tests\Feature;

use App\Livewire\StepViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Tests\TestCase;

class StepViewerAccessTest extends TestCase
{
    public function test_guest_is_redirected_to_login(): void
    {
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $this->get("/courses/{$step->lesson->course->slug}/lessons/{$step->lesson->slug}/steps/{$step->id}")
            ->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_reading_step(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'title' => 'My Reading Step',
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('My Reading Step');
    }

    public function test_step_under_unpublished_course_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

    public function test_step_under_unpublished_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->create(['course_id' => $course->id, 'published' => false]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

    public function test_step_from_wrong_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $otherLesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $otherLesson->id]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

    public function test_nonexistent_step_id_returns_404(): void
    {
        [$user, $course, $lesson] = $this->enrolledUser();

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/999999")
            ->assertNotFound();
    }

    public function test_step_from_wrong_course_lesson_returns_404(): void
    {
        $user = User::factory()->create();
        $courseA = Course::factory()->published()->create();
        $courseA->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $courseB = Course::factory()->published()->create();
        $courseB->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lessonA = Lesson::factory()->published()->create(['course_id' => $courseA->id]);
        $lessonB = Lesson::factory()->published()->create(['course_id' => $courseB->id]);
        $step = Step::factory()->create(['lesson_id' => $lessonB->id]);

        $this->actingAs($user)
            ->get("/courses/{$courseA->slug}/lessons/{$lessonA->slug}/steps/{$step->id}")
            ->assertNotFound();
    }

    public function test_step_viewer_complete_checks_enrollment(): void
    {
        [$user, $course, $lesson, $step] = $this->enrolledUserWithStep();

        $this->actingAs($user);

        $component = new StepViewer;
        $component->course = $course;
        $component->lesson = $lesson;
        $component->step = $step;
        $component->completed = false;

        $course->enrollments()->delete();

        $component->complete();

        expect($component->completed)->toBeFalse();
    }
}
