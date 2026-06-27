<?php

declare(strict_types=1);

namespace App\Actions;

use App\Enums\StepType;
use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;

class SubmitQuizAnswer
{
    /** @param int|string|array<int, int|string>|null $answer */
    public function handle(User $user, Step $step, int|string|array|null $answer): SubmitQuizAnswerResult
    {
        $content = $step->getContentAsArray();

        if ($content === null) {
            StepAnswer::create([
                'user_id' => $user->id,
                'step_id' => $step->id,
                'answer' => '',
                'is_correct' => false,
                'created_at' => now(),
            ]);

            return new SubmitQuizAnswerResult(false, '');
        }

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

        StepAnswer::create([
            'user_id' => $user->id,
            'step_id' => $step->id,
            'answer' => $answerString,
            'is_correct' => $isCorrect,
            'created_at' => now(),
        ]);

        return new SubmitQuizAnswerResult($isCorrect, $answerString);
    }
}
