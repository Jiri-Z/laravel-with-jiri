<?php

declare(strict_types=1);

namespace App\Services;

class AnswerChecker
{
    /**
     * @param  array<string, mixed>  $content
     */
    public function check(string $type, mixed $userAnswer, array $content): bool
    {
        $correctAnswer = $content['answer']
            ?? $content['correct_answer']
            ?? $content['correct_answers']
            ?? null;

        return match ($type) {
            'single' => $this->checkSingle($userAnswer, $correctAnswer),
            'multiple' => $this->checkMultiple($userAnswer, $correctAnswer),
            'text' => $this->checkText($userAnswer, (string) ($correctAnswer ?? ''), $content['alternatives'] ?? []),
            default => false,
        };
    }

    public function checkSingle(mixed $userAnswer, mixed $correctAnswer): bool
    {
        if ($userAnswer === null || $correctAnswer === null) {
            return false;
        }

        return (string) $userAnswer === (string) $correctAnswer;
    }

    public function checkMultiple(mixed $userAnswer, mixed $correctAnswer): bool
    {
        if (! is_array($userAnswer)) {
            return false;
        }

        $correct = $this->resolveArray($correctAnswer);
        $userSet = array_unique($userAnswer);
        $correctSet = array_unique($correct);

        sort($userSet);
        sort($correctSet);

        return $userSet === $correctSet;
    }

    /**
     * @param  list<string>  $alternatives
     */
    public function checkText(mixed $userAnswer, string $correctAnswer, array $alternatives = []): bool
    {
        if (! is_string($userAnswer) || trim($userAnswer) === '') {
            return false;
        }

        $normalize = fn (string $s): string => mb_strtolower(trim($s));
        $normalized = $normalize($userAnswer);

        if ($normalized === $normalize($correctAnswer)) {
            return true;
        }

        foreach ($alternatives as $alt) {
            if (is_string($alt) && $normalized === $normalize($alt)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<mixed>
     */
    private function resolveArray(mixed $value): array
    {
        if (is_array($value)) {
            return array_values($value);
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);

            return is_array($decoded) ? array_values($decoded) : [];
        }

        return [];
    }
}
