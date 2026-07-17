<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\QuestionType;
use Database\Factories\TriviaQuestionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'topic',
    'type',
    'difficulty',
    'question',
    'options',
    'answer',
    'alternatives',
    'explanation',
    'locale',
])]
class TriviaQuestion extends Model
{
    /** @use HasFactory<TriviaQuestionFactory> */
    use HasFactory;

    #[\Override]
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'alternatives' => 'array',
            'type' => QuestionType::class,
        ];
    }
}
