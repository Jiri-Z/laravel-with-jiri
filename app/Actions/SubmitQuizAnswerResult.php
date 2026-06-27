<?php

declare(strict_types=1);

namespace App\Actions;

class SubmitQuizAnswerResult
{
    public function __construct(
        public readonly bool $isCorrect,
        public readonly string $answer,
    ) {}
}
