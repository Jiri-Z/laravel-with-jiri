<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\StepType;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use Illuminate\Database\QueryException;

class SubmitQuizAnswer
{
    /** @param int|string|array<int, int|string>|null $answer */
    public function handle(User $user, Step $step, int|string|array|null $answer): SubmitQuizAnswerResult
    {
        $content = $step->getContentAsArray();

        if ($content === null) {
            $isCorrect = false;
            $answerString = '';
        } else {
            $isCorrect = match ($step->type) {
                StepType::QuizSingle => $answer == ($content['correct_answer'] ?? null),
                StepType::QuizMultiple => is_array($answer)
                    && ! array_diff($answer, $content['correct_answers'] ?? [])
                    && ! array_diff($content['correct_answers'] ?? [], $answer),
                StepType::QuizText => is_string($answer)
                    && strcasecmp(trim($answer), trim($content['correct_answer'] ?? '')) === 0,
                default => false,
            };

            $answerString = match ($step->type) {
                StepType::QuizSingle => (string) $answer,
                StepType::QuizMultiple => json_encode($answer),
                StepType::QuizText => (string) $answer,
                default => '',
            };
        }

        try {
            StepAnswer::create([
                'user_id' => $user->id,
                'step_id' => $step->id,
                'answer' => $answerString,
                'is_correct' => $isCorrect,
                'created_at' => now(),
            ]);
        } catch (QueryException) {
            $existing = StepAnswer::where('user_id', $user->id)
                ->where('step_id', $step->id)
                ->firstOrFail();

            return new SubmitQuizAnswerResult((bool) $existing->is_correct, $existing->answer);
        }

        return new SubmitQuizAnswerResult($isCorrect, $answerString);
    }
}
