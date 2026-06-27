<?php

namespace App\Models;

use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['lesson_id', 'title', 'type', 'content', 'order'])]
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(StepCompletion::class);
    }

    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
