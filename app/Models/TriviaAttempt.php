<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TriviaAttemptFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperTriviaAttempt
 */
#[Fillable([
    'user_id',
    'score',
    'total',
    'answers',
    'completed_at',
])]
class TriviaAttempt extends Model
{
    /** @use HasFactory<TriviaAttemptFactory> */
    use HasFactory;

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'completed_at' => 'datetime',
        ];
    }
}
