<?php

namespace Tests\Unit\Actions;

use App\Actions\MarkStepComplete;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Tests\TestCase;

class MarkStepCompleteTest extends TestCase
{
    public function test_creates_completion_record(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new MarkStepComplete)->handle($user, $step);

        expect($result)->toBeTrue();
        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_does_not_duplicate_completion(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        StepCompletion::create([
            'user_id' => $user->id,
            'step_id' => $step->id,
            'completed_at' => now(),
        ]);

        $result = (new MarkStepComplete)->handle($user, $step);

        expect($result)->toBeFalse();
        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_handles_race_condition_when_row_already_exists(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        // Simulate concurrent request that inserted the record
        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        // This should not throw a QueryException
        $result = (new MarkStepComplete)->handle($user, $step);

        expect($result)->toBeFalse();
        $this->assertDatabaseCount('step_completions', 1);
    }

    public function test_sets_completed_at_timestamp(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        (new MarkStepComplete)->handle($user, $step);

        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);

        $completion = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step->id)
            ->first();

        expect($completion->completed_at)->not->toBeNull();
    }
}
