<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\StepCompletionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperStepCompletion
 */
#[Fillable(['user_id', 'step_id', 'completed_at'])]
class StepCompletion extends Model
{
    /** @use HasFactory<StepCompletionFactory> */
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

    #[\Override]
    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }
}
