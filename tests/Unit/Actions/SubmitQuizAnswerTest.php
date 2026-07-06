<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SubmitQuizAnswer;
use App\Enums\StepType;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Schema;

test('creates answer and returns result', function () {
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
});

test('returns incorrect for wrong answer', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizSingle()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, 0);

    expect($result->isCorrect)->toBeFalse();
    expect($result->answer)->toBe('0');
});

test('handles text answer case insensitive', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizText()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, 'paris');

    expect($result->isCorrect)->toBeTrue();
});

test('quiz multiple with wrong answer returns incorrect', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizMultiple()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, [1, 2]);

    expect($result->isCorrect)->toBeFalse();
});

test('quiz multiple with partial selection returns incorrect', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizMultiple()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, [0]);

    expect($result->isCorrect)->toBeFalse();
});

test('handles null answer for quiz single', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizSingle()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, null);

    expect($result->isCorrect)->toBeFalse();
});

test('handles empty answer for quiz text', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizText()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, '');

    expect($result->isCorrect)->toBeFalse();
});

test('handles malformed step content gracefully', function () {
    $user = User::factory()->create();
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        'type' => StepType::Quiz,
        'content' => '{invalid json}',
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);

    expect($result->isCorrect)->toBeFalse();
    $this->assertDatabaseHas('step_answers', [
        'user_id' => $user->id,
        'step_id' => $step->id,
        'is_correct' => false,
    ]);
});

test('reading step type defaults to incorrect', function () {
    $user = User::factory()->create();
    $step = Step::factory()->reading()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, 'anything');

    expect($result->isCorrect)->toBeFalse();
    expect($result->answer)->toBe('');
});

test('can be invoked twice without exception', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizSingle()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    (new SubmitQuizAnswer)->handle($user, $step, 1);

    (new SubmitQuizAnswer)->handle($user, $step, 1);

    $this->assertDatabaseCount('step_answers', 1);
});

test('handles quiz type single question', function () {
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
});

test('handles quiz type multiple questions', function () {
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
});

test('quiz type incorrect answer', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quiz()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);

    expect($result->isCorrect)->toBeFalse();
});

test('question indices are independent', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quiz()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);
    (new SubmitQuizAnswer)->handle($user, $step, 'Paris', questionIndex: 1);

    (new SubmitQuizAnswer)->handle($user, $step, 0, questionIndex: 0);

    $this->assertDatabaseCount('step_answers', 2);
});

test('duplicate submission returns existing result even with different answer', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizSingle()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    $result1 = (new SubmitQuizAnswer)->handle($user, $step, 1);

    $result2 = (new SubmitQuizAnswer)->handle($user, $step, 0);

    expect($result2->isCorrect)->toBeTrue();
    expect($result2->answer)->toBe('1');
    $this->assertDatabaseCount('step_answers', 1);
});

test('non-duplicate db error propagates', function () {
    $user = User::factory()->create();
    $step = Step::factory()->quizSingle()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
    ]);

    // Create the first answer so the duplicate handler is triggered
    (new SubmitQuizAnswer)->handle($user, $step, 1);

    // Drop the table to cause a different error (table not found) instead of
    // the duplicate-key error. Non-duplicate errors must propagate.
    $connection = Schema::getConnection();
    $rawCreate = $connection->selectOne("SELECT sql FROM sqlite_master WHERE type='table' AND name='step_answers'");
    $createSql = $rawCreate->sql;
    Schema::drop('step_answers');

    expect(fn () => (new SubmitQuizAnswer)->handle($user, $step, 0))
        ->toThrow(QueryException::class);

    // Restore the table so subsequent tests in this file don't break
    $connection->statement($createSql);
});

test('is correct not mass assignable', function () {
    $model = new StepAnswer;
    $fillable = $model->getFillable();

    $this->assertNotContains('is_correct', $fillable);
});

test('handles new format answer', function (array $data) {
    $user = User::factory()->create();
    $step = Step::factory()->create([
        'lesson_id' => Lesson::factory()->create(['course_id' => Course::factory()]),
        'type' => StepType::Quiz,
        'content' => json_encode([$data]),
    ]);

    $result = (new SubmitQuizAnswer)->handle($user, $step, $data['userAnswer']);

    expect($result->isCorrect)->toBeTrue();
})->with('quiz_answer_formats');
