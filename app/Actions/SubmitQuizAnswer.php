<?php

declare(strict_types=1);

namespace App\Actions;

use App\Exceptions\NotEnrolledException;
use App\Exceptions\OrphanedStepException;
use App\Models\CourseEnrollment;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use App\Services\AnswerChecker;
use Illuminate\Database\QueryException;

class SubmitQuizAnswer
{
    /**
     * @param  array<int, mixed>|int|string|bool|null  $answer
     */
    public function handle(User $user, Step $step, int|string|array|bool|null $answer, int $questionIndex = 0): SubmitQuizAnswerResult
    {
        $normalizedAnswer = $this->normalizeAnswer($answer);

        $lesson = $step->lesson;
        if ($lesson === null) {
            throw new OrphanedStepException;
        }

        $course = $lesson->course;
        if ($course === null) {
            throw new OrphanedStepException;
        }

        $enrolled = CourseEnrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if (! $enrolled) {
            throw new NotEnrolledException;
        }

        $content = $this->resolveContent($step, $questionIndex);

        if ($content === null) {
            $isCorrect = false;
            $answerString = '';
        } else {
            $questionType = $this->resolveQuestionType($content);
            $isCorrect = $this->checkAnswer($questionType, $content, $normalizedAnswer);
            $answerString = $this->serializeAnswer($questionType, $normalizedAnswer);
        }

        try {
            $stepAnswer = new StepAnswer;
            $stepAnswer->user_id = $user->id;
            $stepAnswer->step_id = $step->id;
            $stepAnswer->question_index = $questionIndex;
            $stepAnswer->answer = $answerString;
            $stepAnswer->is_correct = $isCorrect;
            $stepAnswer->created_at = now();
            $stepAnswer->save();
        } catch (QueryException $e) {
            if (! in_array((string) $e->getCode(), ['23000', '23505'], true)) {
                throw $e;
            }

            $existing = StepAnswer::where('user_id', $user->id)
                ->where('step_id', $step->id)
                ->where('question_index', $questionIndex)
                ->firstOrFail();

            return new SubmitQuizAnswerResult((bool) $existing->is_correct, $existing->answer);
        }

        return new SubmitQuizAnswerResult($isCorrect, $answerString);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resolveContent(Step $step, int $questionIndex): ?array
    {
        $questions = $step->getContentAsArray();

        if ($questions === null) {
            return null;
        }

        $question = $questions[$questionIndex] ?? null;

        /** @var array<string, mixed>|null */
        return $question;
    }

    /**
     * @param  array<string, mixed>  $content
     */
    private function resolveQuestionType(array $content): string
    {
        $type = $content['type'] ?? 'single';

        return is_string($type) ? $type : 'single';
    }

    /**
     * @param  array<string, mixed>  $content
     * @param  array<int, mixed>|int|string|null  $answer
     */
    private function checkAnswer(string $questionType, array $content, int|string|array|null $answer): bool
    {
        return (new AnswerChecker)->check($questionType, $answer, $content);
    }

    /**
     * @param  array<int, mixed>|int|string|bool|null  $answer
     * @return array<int, mixed>|int|string|null
     */
    private function normalizeAnswer(int|string|array|bool|null $answer): int|string|array|null
    {
        return match (true) {
            is_string($answer), is_int($answer), is_array($answer) => $answer,
            default => null,
        };
    }

    /**
     * @param  array<int, mixed>|int|string|null  $answer
     */
    private function serializeAnswer(string $questionType, int|string|array|null $answer): string
    {
        return match ($questionType) {
            'single' => is_string($answer) || is_int($answer) ? (string) $answer : '',
            'multiple' => is_array($answer) ? (json_encode($answer) ?: '') : '',
            'text' => is_string($answer) || is_int($answer) ? (string) $answer : '',
            default => '',
        };
    }
}
