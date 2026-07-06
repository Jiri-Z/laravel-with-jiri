<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use App\Services\AnswerChecker;
use Illuminate\Database\QueryException;

class SubmitQuizAnswer
{
    /**
     * @param  array<int, mixed>|int|string|null  $answer
     */
    public function handle(User $user, Step $step, int|string|array|null $answer, int $questionIndex = 0): SubmitQuizAnswerResult
    {
        $content = $this->resolveContent($step, $questionIndex);

        if ($content === null) {
            $isCorrect = false;
            $answerString = '';
        } else {
            $questionType = $this->resolveQuestionType($content);
            $isCorrect = $this->checkAnswer($questionType, $content, $answer);
            $answerString = $this->serializeAnswer($questionType, $answer);
        }

        try {
            $answer = new StepAnswer;
            $answer->user_id = max(0, (int) $user->id);
            $answer->step_id = max(0, (int) $step->id);
            $answer->question_index = max(0, $questionIndex);
            $answer->answer = $answerString;
            $answer->is_correct = $isCorrect;
            $answer->created_at = now();
            $answer->save();
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

        return $questions[$questionIndex] ?? null;
    }

    /**
     * @param  array<string, mixed>  $content
     */
    private function resolveQuestionType(array $content): string
    {
        return $content['type'] ?? 'single';
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
