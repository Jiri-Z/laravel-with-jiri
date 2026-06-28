<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StepType;
use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property StepType $type
 *
 * @method static Builder<self> ordered()
 */
#[Fillable(['lesson_id', 'title', 'type', 'content', 'order'])]
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

    /** @return BelongsTo<Lesson, $this> */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /** @return HasMany<StepCompletion, $this> */
    public function completions(): HasMany
    {
        return $this->hasMany(StepCompletion::class);
    }

    /** @return HasMany<StepAnswer, $this> */
    public function answers(): HasMany
    {
        return $this->hasMany(StepAnswer::class);
    }

    /**
     * @param  Builder<Step>  $query
     * @return Builder<Step>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * @param  Builder<Step>  $query
     * @return Builder<Step>
     */
    #[Scope]
    protected function search(Builder $query, string $term): Builder
    {
        if (strlen($term) < 2) {
            return $query;
        }

        return $query->where(fn (Builder $q): Builder => $q->where('title', 'like', "%{$term}%"));
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'type' => StepType::class,
        ];
    }

    /** @return array<string, mixed>|null */
    public function getContentAsArray(): ?array
    {
        if (str_starts_with($this->content, '{') || str_starts_with($this->content, '[')) {
            return json_decode($this->content, true);
        }

        return null;
    }
}
