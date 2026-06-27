<?php

namespace Tests\Feature;

use App\Livewire\StepViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

class StepViewerTest extends TestCase
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
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create([
            'lesson_id' => $lesson->id,
            'title' => 'My Reading Step',
        ]);

        $response = $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$step->id}");

        $response->assertOk();
        $response->assertSee('My Reading Step');
    }

    public function test_user_can_complete_a_reading_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('complete')
            ->assertSet('completed', true);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_user_cannot_complete_same_step_twice(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('complete')
            ->assertSet('completed', true);

        $this->assertDatabaseCount('step_completions', 1);
    }
}
