<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOrder;
use Database\Factories\LessonFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
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

    /** @use HasOrder<self> */
    use HasOrder;

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

    /** @param Builder<self> $query
     * @return Builder<self> */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('published', true);
    }

    /** @param Builder<self> $query
     * @return Builder<self> */
    #[Scope]
    protected function search(Builder $query, string $term): Builder
    {
        if (mb_strlen($term) < 2) {
            return $query;
        }

        return $query->where(fn (Builder $q): Builder => $q->where('title', 'like', "%{$term}%")
            ->orWhere('slug', 'like', "%{$term}%"));
    }

    public function hasUserCompletedPreviousStep(User $user, Step $step): bool
    {
        $previousStep = $this->steps()
            ->where('order', '<', $step->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousStep === null) {
            return true;
        }

        return StepCompletion::where('user_id', $user->id)
            ->where('step_id', $previousStep->id)
            ->exists();
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'order' => 'integer',
        ];
    }
}
