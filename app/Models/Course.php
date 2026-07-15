<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasOrder;
use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Builder<self> forCurrentLocale()
 * @method static Builder<self> published()
 * @method static Builder<self> ordered()
 * @method static Builder<self> ownedBy(User $user)
 */
#[Fillable(['title', 'slug', 'description', 'published', 'order', 'user_id', 'locale'])]
class Course extends Model
{
    /** @use HasFactory<CourseFactory> */
    use HasFactory;

    /** @use HasOrder<self> */
    use HasOrder;

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** @return HasMany<Lesson, $this> */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    /** @return HasMany<CourseEnrollment, $this> */
    public function enrollments(): HasMany
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    /** @param Builder<self> $query
     * @return Builder<self> */
    #[Scope]
    protected function forCurrentLocale(Builder $query): Builder
    {
        return $query->where('locale', app()->getLocale());
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
    protected function ownedBy(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
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

    #[\Override]
    protected function casts(): array
    {
        return [
            'published' => 'boolean',
            'order' => 'integer',
        ];
    }
}
