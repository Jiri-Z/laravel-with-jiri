<?php

namespace Tests\Unit\Actions;

use App\Actions\MarkStepComplete;
use App\Exceptions\CourseNotPublishedException;
use App\Exceptions\NotEnrolledException;
use App\Exceptions\StepNotAccessibleException;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Tests\TestCase;

class MarkStepCompleteTest extends TestCase
{
    private function createEnrolledUserStep(): array
    {
        $course = Course::factory()->published()->create();
        $user = User::factory()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->create(['lesson_id' => $lesson->id]);

        return [$user, $step];
    }

    public function test_creates_completion_record(): void
    {
        [$user, $step] = $this->createEnrolledUserStep();

        $result = (new MarkStepComplete)->handle($user, $step);

        expect($result)->toBeTrue();
        $this->assertDatabaseHas('step_completions', [
            'user_id' => $user->id,
            'step_id' => $step->id,
        ]);
    }

    public function test_does_not_duplicate_completion(): void
    {
        [$user, $step] = $this->createEnrolledUserStep();

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
        [$user, $step] = $this->createEnrolledUserStep();

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
        [$user, $step] = $this->createEnrolledUserStep();

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

    public function test_blocks_inaccessible_step(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $secondStep = Step::factory()->reading()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        $this->expectException(StepNotAccessibleException::class);

        (new MarkStepComplete)->handle($user, $secondStep);
    }

    public function test_blocks_unenrolled_user(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->published()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        $this->expectException(NotEnrolledException::class);

        (new MarkStepComplete)->handle($user, $step);
    }

    public function test_blocks_unpublished_course(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['published' => false]);
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $step = Step::factory()->reading()->create(['lesson_id' => $lesson->id]);

        $this->expectException(CourseNotPublishedException::class);

        (new MarkStepComplete)->handle($user, $step);
    }
}
