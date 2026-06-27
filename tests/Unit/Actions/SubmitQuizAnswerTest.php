<?php

namespace Tests\Unit\Actions;

use App\Actions\SubmitQuizAnswer;
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
}
