<?php

namespace Tests\Unit\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use App\Services\ProgressService;
use Illuminate\Support\Collection;
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

    public function test_course_progress_excludes_unpublished_lessons(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $publishedLesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $unpublishedLesson = Lesson::factory()->create(['course_id' => $course->id, 'published' => false]);

        Step::factory()->create(['lesson_id' => $publishedLesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $publishedLesson->id, 'order' => 2]);
        Step::factory()->create(['lesson_id' => $unpublishedLesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $unpublishedLesson->id, 'order' => 2]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $publishedLesson->steps()->first()->id]);
        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $publishedLesson->steps()->where('order', 2)->first()->id]);

        expect((new ProgressService)->courseProgress($user, $course))->toBe(100.0);
    }

    public function test_returns_correct_percentage_for_course_with_partial_completion(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
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
        $lesson1 = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $lesson2 = Lesson::factory()->published()->create(['course_id' => $course->id]);
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
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);

        expect((new ProgressService)->courseProgress($user, $course))->toBe(0.0);
    }

    public function test_course_progress_rounding(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 3]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 4]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 5]);
        Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 6]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $lesson->steps()->first()->id]);

        expect((new ProgressService)->courseProgress($user, $course))->toBe(16.7);
    }

    public function test_course_progress_batch_matches_individual_calls(): void
    {
        $user = User::factory()->create();
        $courseA = Course::factory()->create();
        $courseB = Course::factory()->create();
        $lessonA = Lesson::factory()->published()->create(['course_id' => $courseA->id]);
        $lessonB = Lesson::factory()->published()->create(['course_id' => $courseB->id]);
        $stepA = Step::factory()->create(['lesson_id' => $lessonA->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lessonA->id, 'order' => 2]);
        Step::factory()->create(['lesson_id' => $lessonB->id, 'order' => 1]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $stepA->id]);

        $service = new ProgressService;
        $batch = $service->courseProgressBatch($user, collect([$courseA, $courseB]));

        expect($batch)->toHaveKeys([$courseA->id, $courseB->id]);
        expect($batch[$courseA->id])->toBe(50.0);
        expect($batch[$courseB->id])->toBe(0.0);
    }

    public function test_step_complete_batch_returns_correct_map(): void
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => Course::factory()]);
        $step1 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 1]);
        $step2 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 2]);
        $step3 = Step::factory()->create(['lesson_id' => $lesson->id, 'order' => 3]);

        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step1->id]);
        StepCompletion::factory()->create(['user_id' => $user->id, 'step_id' => $step3->id]);

        $service = new ProgressService;
        $batch = $service->stepCompleteBatch($user, new Collection([$step1, $step2, $step3]));

        expect($batch)->toHaveKeys([$step1->id, $step2->id, $step3->id]);
        expect($batch[$step1->id])->toBeTrue();
        expect($batch[$step2->id])->toBeFalse();
        expect($batch[$step3->id])->toBeTrue();
    }

    public function test_lesson_complete_batch_matches_individual_calls(): void
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lessonA = Lesson::factory()->published()->create(['course_id' => $course->id]);
        $lessonB = Lesson::factory()->published()->create(['course_id' => $course->id]);
        Step::factory()->create(['lesson_id' => $lessonA->id, 'order' => 1]);
        Step::factory()->create(['lesson_id' => $lessonA->id, 'order' => 2]);
        Step::factory()->create(['lesson_id' => $lessonB->id, 'order' => 1]);

        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $lessonA->steps()->first()->id,
        ]);
        StepCompletion::factory()->create([
            'user_id' => $user->id,
            'step_id' => $lessonA->steps()->where('order', 2)->first()->id,
        ]);

        $service = new ProgressService;
        $batch = $service->lessonCompleteBatch($user, collect([$lessonA, $lessonB]));

        expect($batch)->toHaveKeys([$lessonA->id, $lessonB->id]);
        expect($batch[$lessonA->id])->toBeTrue();
        expect($batch[$lessonB->id])->toBeFalse();
    }
}
