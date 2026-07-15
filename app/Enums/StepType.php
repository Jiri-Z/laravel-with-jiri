<?php

declare(strict_types=1);

namespace App\Enums;

enum StepType: string
{
    case Reading = 'reading';
    case Quiz = 'quiz';
}
