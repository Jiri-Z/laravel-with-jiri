<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StepAnswerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Override;

/**
 * @property int $user_id
 * @property int $step_id
 * @property int $question_index
 * @property string $answer
 * @property bool $is_correct
 * @property Carbon|null $created_at
 */
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

    #[Override]
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'created_at' => 'datetime',
        ];
    }
}
