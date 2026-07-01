<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use Illuminate\Database\QueryException;

class SubmitQuizAnswer
{
    /**
     * @param  int|string|array<int, int|string>|null  $answer
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
            $answer->user_id = $user->id;
            $answer->step_id = $step->id;
            $answer->question_index = $questionIndex;
            $answer->answer = $answerString;
            $answer->is_correct = $isCorrect;
            $answer->created_at = now();
            $answer->save();
        } catch (QueryException) {
            $existing = StepAnswer::where('user_id', $user->id)
                ->where('step_id', $step->id)
                ->where('question_index', $questionIndex)
                ->firstOrFail();

            return new SubmitQuizAnswerResult((bool) $existing->is_correct, $existing->answer);
        }

        return new SubmitQuizAnswerResult($isCorrect, $answerString);
    }

    /** @return array<string, mixed>|null */
    private function resolveContent(Step $step, int $questionIndex): ?array
    {
        /** @var array<int, array<string, mixed>>|null $questions */
        $questions = $step->getContentAsArray();

        if ($questions === null) {
            return null;
        }

        return $questions[$questionIndex] ?? null;
    }

    /** @param  array<string, mixed>  $content */
    private function resolveQuestionType(array $content): string
    {
        return $content['type'] ?? 'single';
    }

    /**
     * @param  array<string, mixed>  $content
     * @param  int|string|array<int, int|string>|null  $answer
     */
    private function checkAnswer(string $questionType, array $content, int|string|array|null $answer): bool
    {
        return match ($questionType) {
            'single' => $answer == ($content['correct_answer'] ?? null),
            'multiple' => is_array($answer)
                && ! array_diff($answer, $content['correct_answers'] ?? [])
                && ! array_diff($content['correct_answers'] ?? [], $answer),
            'text' => is_string($answer)
                && strcasecmp(trim($answer), trim($content['correct_answer'] ?? '')) === 0,
            default => false,
        };
    }

    /**
     * @param  int|string|array<int, int|string>|null  $answer
     */
    private function serializeAnswer(string $questionType, int|string|array|null $answer): string
    {
        return match ($questionType) {
            'single' => (string) $answer,
            'multiple' => json_encode($answer),
            'text' => (string) $answer,
            default => '',
        };
    }
}
