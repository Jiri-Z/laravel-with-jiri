<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Actions\MarkStepComplete;
use App\Livewire\StepViewer;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Tests\TestCase;

class StepCompletionToggleTest extends TestCase
{
    public function test_user_can_uncheck_a_reading_step(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps();
        $step = $steps[1];

        (new MarkStepComplete)->handle($user, $step);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->assertSet('completed', true)
            ->call('toggleComplete')
            ->assertSet('completed', false);

        $completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step->id)
            ->first();

        expect($completion)->not->toBeNull();
        expect($completion->completed_at)->toBeNull();
        expect($completion->unlocked_at)->toBeNull();
    }

    public function test_unchecked_step_can_be_recompleted(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps();
        $step = $steps[1];

        (new MarkStepComplete)->handle($user, $step);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('toggleComplete')
            ->assertSet('completed', false);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $step,
            ])
            ->call('toggleComplete')
            ->assertSet('completed', true);

        $completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step->id)
            ->first();

        expect($completion->completed_at)->not->toBeNull();
        expect($completion->unlocked_at)->not->toBeNull();
    }

    public function test_completing_step_unlocks_next_step(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps(2);
        $step1 = $steps[1];
        $step2 = $steps[2];

        (new MarkStepComplete)->handle($user, $step1);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step2->id,
        ]);

        $completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step2->id)
            ->first();

        expect($completion->unlocked_at)->not->toBeNull();
        expect($completion->completed_at)->toBeNull();
    }

    public function test_unchecking_locks_immediate_next_step(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps(3);

        (new MarkStepComplete)->handle($user, $steps[1]);
        (new MarkStepComplete)->handle($user, $steps[2]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $steps[1],
            ])
            ->call('toggleComplete')
            ->assertSet('completed', false);

        // Step2's unlocked_at should be nulled (re-locked)
        $step2Completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $steps[2]->id)
            ->first();

        expect($step2Completion->unlocked_at)->toBeNull();

        // Step2 is now locked
        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$steps[2]->id}")
            ->assertRedirect("/courses/{$course->slug}/lessons/{$lesson->slug}");

        // Re-complete step1 to unlock the chain again
        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $steps[1],
            ])
            ->call('toggleComplete')
            ->assertSet('completed', true);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}/steps/{$steps[2]->id}")
            ->assertOk();
    }

    public function test_unchecking_updates_progress(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps(2);

        (new MarkStepComplete)->handle($user, $steps[1]);
        (new MarkStepComplete)->handle($user, $steps[2]);

        $progress = App::make(ProgressService::class);

        expect($progress->courseProgress($user, $course))->toBe(100.0);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $steps[2],
            ])
            ->call('toggleComplete')
            ->assertSet('completed', false);

        expect($progress->courseProgress($user, $course))->toBe(50.0);
    }

    public function test_lesson_detail_reflects_toggle_state(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps(2);
        $lesson->title = 'Test Lesson';
        $lesson->save();
        $steps[1]->title = 'Step One';
        $steps[1]->save();
        $steps[2]->title = 'Step Two';
        $steps[2]->save();

        (new MarkStepComplete)->handle($user, $steps[1]);

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
            ->assertSee(__('lessons.completed'));

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $steps[1],
            ])
            ->call('toggleComplete');

        $this->actingAs($user)
            ->get("/courses/{$course->slug}/lessons/{$lesson->slug}")
            ->assertDontSee(__('lessons.completed'));
    }

    public function test_unchecking_step_relocks_next_step(): void
    {
        [$user, $course, $lesson, $steps] = $this->enrolledMultiSteps(3);

        (new MarkStepComplete)->handle($user, $steps[1]);
        (new MarkStepComplete)->handle($user, $steps[2]);
        (new MarkStepComplete)->handle($user, $steps[3]);

        Livewire::actingAs($user)
            ->test(StepViewer::class, [
                'course' => $course,
                'lesson' => $lesson,
                'step' => $steps[1],
            ])
            ->call('toggleComplete')
            ->assertSet('completed', false);

        // Step2 should be re-locked (unlocked_at = null)
        $step2Completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $steps[2]->id)
            ->first();

        expect($step2Completion->completed_at)->not->toBeNull();
        expect($step2Completion->unlocked_at)->toBeNull();

        // Step3 should still be unlocked (not cascading)
        $step3Completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $steps[3]->id)
            ->first();

        expect($step3Completion->unlocked_at)->not->toBeNull();
    }

    /** @return array{0: User, 1: Course, 2: Lesson, 3: array<int, Step>} */
    private function enrolledMultiSteps(int $count = 1): array
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);

        $steps = [];
        for ($i = 1; $i <= $count; $i++) {
            $steps[$i] = Step::factory()->reading()->create([
                'lesson_id' => $lesson->id,
                'order' => $i,
            ]);
        }

        return [$user, $course, $lesson, $steps];
    }
}
