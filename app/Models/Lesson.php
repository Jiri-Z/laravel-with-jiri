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

#[Fillable(['course_id', 'title', 'slug', 'description', 'published', 'order'])]
class Lesson extends Model
{
    /** @use HasFactory<LessonFactory> */
    use HasFactory;

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function steps(): HasMany
    {
        return $this->hasMany(Step::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }

    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'order' => 'integer',
        ];
    }
}
