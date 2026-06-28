<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SubmitQuizAnswer;
use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\User;
use Tests\TestCase;

class SubmitQuizAnswerTest extends TestCase
{
    public function test_creates_answer_and_returns_result(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizSingle()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 1);

        expect($result->isCorrect)->toBeTrue();
        expect($result->answer)->toBe('1');
        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'question_index' => 0,
            'is_correct' => true,
        ]);
    }

    public function test_returns_incorrect_for_wrong_answer(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizSingle()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 0);

        expect($result->isCorrect)->toBeFalse();
        expect($result->answer)->toBe('0');
    }

    public function test_handles_text_answer_case_insensitive(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizText()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 'paris');

        expect($result->isCorrect)->toBeTrue();
    }

    public function test_quiz_multiple_with_wrong_answer_returns_incorrect(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizMultiple()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, [1, 2]);

        expect($result->isCorrect)->toBeFalse();
    }

    public function test_quiz_multiple_with_partial_selection_returns_incorrect(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizMultiple()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, [0]);

        expect($result->isCorrect)->toBeFalse();
    }

    public function test_handles_null_answer_for_quiz_single(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizSingle()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, null);

        expect($result->isCorrect)->toBeFalse();
    }

    public function test_handles_empty_answer_for_quiz_text(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizText()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, '');

        expect($result->isCorrect)->toBeFalse();
    }

    public function test_handles_malformed_step_content_gracefully(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
            'type' => StepType::QuizSingle,
            'content' => '{invalid json}',
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 0);

        expect($result->isCorrect)->toBeFalse();
        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'is_correct' => false,
        ]);
    }

    public function test_reading_step_type_defaults_to_incorrect(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->reading()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 'anything');

        expect($result->isCorrect)->toBeFalse();
        expect($result->answer)->toBe('');
    }

    public function test_can_be_invoked_twice_without_exception(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quizSingle()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        (new SubmitQuizAnswer)->handle($user, $step, 1);

        // Second call with same user and step should not throw
        (new SubmitQuizAnswer)->handle($user, $step, 1);

        $this->assertDatabaseCount('step_answers', 1);
    }

    public function test_handles_quiz_type_single_question(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quiz()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 1, questionIndex: 0);

        expect($result->isCorrect)->toBeTrue();
        expect($result->answer)->toBe('1');
        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'question_index' => 0,
            'is_correct' => true,
        ]);
    }

    public function test_handles_quiz_type_multiple_questions(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quiz()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result0 = (new SubmitQuizAnswer)->handle($user, $step, 1, questionIndex: 0);
        $result1 = (new SubmitQuizAnswer)->handle($user, $step, 'Paris', questionIndex: 1);
        $result2 = (new SubmitQuizAnswer)->handle($user, $step, [0, 3], questionIndex: 2);

        expect($result0->isCorrect)->toBeTrue();
        expect($result1->isCorrect)->toBeTrue();
        expect($result2->isCorrect)->toBeTrue();

        $this->assertDatabaseCount('step_answers', 3);
        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'question_index' => 0,
            'is_correct' => true,
        ]);
        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'question_index' => 1,
            'is_correct' => true,
        ]);
        $this->assertDatabaseHas('step_answers', [
            'user_id' => $user->id,
            'step_id' => $step->id,
            'question_index' => 2,
            'is_correct' => true,
        ]);
    }

    public function test_quiz_type_incorrect_answer(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quiz()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        $result = (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);

        expect($result->isCorrect)->toBeFalse();
    }

    public function test_quiz_type_question_indices_are_independent(): void
    {
        $user = User::factory()->create();
        $step = Step::factory()->quiz()->create([
            'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        ]);

        (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);
        (new SubmitQuizAnswer)->handle($user, $step, 'Paris', questionIndex: 1);

        // Re-submitting same index should not duplicate
        (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);

        $this->assertDatabaseCount('step_answers', 2);
    }
}
