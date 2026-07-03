<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
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
    #[\Override]
    protected function casts(): array
    {
        return [
            'options' => 'array',
            'alternatives' => 'array',
        ];
    }
}
