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
        $correctAnswerText = is_string($correctAnswer) ? $correctAnswer : '';

        $alternatives = [];
        if (is_array($content['alternatives'] ?? null)) {
            foreach ($content['alternatives'] as $alt) {
                $alternatives[] = is_string($alt) ? $alt : $this->stringify($alt);
            }
        }

        return match ($type) {
            'single' => $this->checkSingle($userAnswer, $correctAnswer),
            'multiple' => $this->checkMultiple($userAnswer, $correctAnswer),
            'text' => $this->checkText($userAnswer, $correctAnswerText, $alternatives),
            default => false,
        };
    }

    public function checkSingle(mixed $userAnswer, mixed $correctAnswer): bool
    {
        if ($userAnswer === null || $correctAnswer === null) {
            return false;
        }

        if (is_bool($userAnswer) || is_bool($correctAnswer)) {
            return is_bool($userAnswer) && is_bool($correctAnswer) && $userAnswer === $correctAnswer;
        }

        return $this->stringify($userAnswer) === $this->stringify($correctAnswer);
    }

    public function checkMultiple(mixed $userAnswer, mixed $correctAnswer): bool
    {
        if (! is_array($userAnswer)) {
            return false;
        }

        $correct = $this->resolveArray($correctAnswer);
        $userSet = array_map($this->stringify(...), $userAnswer);
        $correctSet = array_map($this->stringify(...), $correct);
        $userUnique = array_unique($userSet);
        $correctUnique = array_unique($correctSet);

        sort($userUnique);
        sort($correctUnique);

        return $userUnique === $correctUnique;
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

        return array_any($alternatives, fn ($alt) => is_string($alt) && $normalized === $normalize($alt));
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

    private function stringify(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value)) {
            return (string) $value;
        }

        if (is_bool($value)) {
            return $value ? '1' : '';
        }

        return '';
    }
}
