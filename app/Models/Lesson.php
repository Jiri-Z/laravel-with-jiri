<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Builder<self> published()
 * @method static Builder<self> ordered()
 */
#[Fillable(['course_id', 'title', 'slug', 'description', 'published', 'order'])]
class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    /** @return BelongsTo<Course, $this> */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /** @return HasMany<Step, $this> */
    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    /**
     * @param  Builder<Lesson>  $query
     * @return Builder<Lesson>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /**
     * @param  Builder<Lesson>  $query
     * @return Builder<Lesson>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    /**
     * @param  Builder<Lesson>  $query
     * @return Builder<Lesson>
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        if (strlen($term) < 2) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term): Builder {
            return $q->where('title', 'like', "%{$term}%")
                ->orWhere('slug', 'like', "%{$term}%");
        });
    }

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'order' => 'integer',
        ];
    }
}
