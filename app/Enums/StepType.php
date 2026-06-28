<?php

declare(strict_types=1);

namespace App\Enums;

enum StepType: string
{
    case Reading = 'reading';
    case Quiz = 'quiz';
    case QuizSingle = 'quiz_single';
    case QuizMultiple = 'quiz_multiple';
    case QuizText = 'quiz_text';
    case Coding = 'coding';
}
