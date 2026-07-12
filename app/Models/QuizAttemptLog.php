<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\QuizAttemptLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'step_id',
    'score',
    'total',
    'answers',
    'attempted_at',
])]
class QuizAttemptLog extends Model
{
    /** @use HasFactory<QuizAttemptLogFactory> */
    use HasFactory;

    #[\Override]
    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'attempted_at' => 'datetime',
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
