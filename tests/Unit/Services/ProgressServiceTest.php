<?php

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use App\Services\ProgressService;
use Tests\TestCase;

class ProgressServiceTest extends TestCase
{
    public function test_returns_zero_for_course_with_no_steps(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $progress = (new ProgressService)->courseProgress($user, $course);

        expect($progress)->toBe(0.0);
    }

    public function test_returns_correct_percentage_for_course_with_partial_completion(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $step1->id,
        ]);

        $progress = (new ProgressService)->courseProgress($user, $course);

        expect($progress)->toBe(50.0);
    }

    public function test_returns_hundred_for_course_with_all_steps_completed(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step1->id]);
        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step2->id]);

        $progress = (new ProgressService)->courseProgress($user, $course);

        expect($progress)->toBe(100.0);
    }

    public function test_lesson_is_complete_when_all_steps_completed(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step1->id]);
        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step2->id]);

        expect((new ProgressService)->lessonComplete($user, $lesson))->toBeTrue();
    }

    public function test_lesson_is_not_complete_when_some_steps_missing(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step1->id]);

        expect((new ProgressService)->lessonComplete($user, $lesson))->toBeFalse();
    }

    public function test_lesson_with_zero_steps_is_not_complete(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);

        expect((new ProgressService)->lessonComplete($user, $lesson))->toBeFalse();
    }

    public function test_course_progress_across_multiple_lessons(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson1 = Lesson::factory()->create(['course_id' => $course->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson1->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson1->id, 'order' => 2]);
        Step::factory()->create(['lesson_id' => $lesson2->id, 'order' => 1]);
        $step4 = Step::factory()->create(['lesson_id' => $lesson2->id, 'order' => 2]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step4->id]);

        expect((new ProgressService)->courseProgress($user, $course))->toBe(25.0);
    }

    public function test_course_progress_with_steps_and_no_completions(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        expect((new ProgressService)->courseProgress($user, $course))->toBe(0.0);
    }

    public function test_course_progress_rounding(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 3]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 4]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 5]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 6]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $lesson->steps()->first()->id]);

        expect((new ProgressService)->courseProgress($user, $course))->toBe(16.7);
    }
}
