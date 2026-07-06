<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StepAnswerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'step_id',
    'answer',
    'question_index',
])]
#[WithoutTimestamps]
class StepAnswer extends Model
{
    /** @use HasFactory<StepAnswerFactory> */
    use HasFactory;

    #[\Override]
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<Step, $this> */
    public function step(): BelongsTo
    {
        return $this->belongsTo(Step::class);
    }
}
