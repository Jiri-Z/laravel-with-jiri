<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StepType;
use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
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
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term): Builder {
            return $q->where('title', 'like', "%{$term}%")
                ->orWhere('type', 'like', "%{$term}%");
        });
    }

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
        if (str_starts_with($this->content, '{')) {
            return json_decode($this->content, true);
        }

        return null;
    }
}
