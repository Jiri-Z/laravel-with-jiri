<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StepType;
use App\Models\Concerns\HasOrder;
use Database\Factories\StepFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static Builder<self> ordered()
 * @method static Builder<self> published()
 * @method static Builder<self> ownedBy(User $user)
 */
#[Fillable(['lesson_id', 'title', 'type', 'reading_content', 'quiz_content', 'order', 'published'])]
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

    /** @use HasOrder<self> */
    use HasOrder;

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
        return $query->whereHas('lesson.course', fn (Builder $q): Builder => $q->where('user_id', $user->id));
    }

    /** @param Builder<self> $query
     * @return Builder<self> */
    #[Scope]
    protected function search(Builder $query, string $term): Builder
    {
        if (mb_strlen($term) < 2) {
            return $query;
        }

        $escaped = str_replace(['%', '_'], ['\\%', '\\_'], $term);
        $pattern = "%{$escaped}%";

        return $query->where(fn (Builder $q): Builder => $q->whereRaw('title LIKE ? ESCAPE ?', [$pattern, '\\']));
    }

    public function isAccessibleBy(User $user): bool
    {
        return $this->lesson?->hasUserUnlockedPreviousStep($user, $this) ?? false;
    }

    #[\Override]
    protected function casts(): array
    {
        return [
            'order' => 'integer',
            'type' => StepType::class,
            'published' => 'boolean',
        ];
    }

    /** @return ?array<int|string, mixed> */
    public function getContentAsArray(): ?array
    {
        $source = match ($this->type) {
            StepType::Quiz => $this->quiz_content,
            default => null,
        };

        if (empty($source) || ! json_validate($source)) {
            return null;
        }

        $decoded = json_decode($source, true);

        if (! is_array($decoded)) {
            return null;
        }

        if (array_is_list($decoded)) {
            return $decoded;
        }

        $question = $decoded['question'] ?? null;
        $options = $decoded['options'] ?? null;
        $answer = $decoded['answer'] ?? null;

        if (! is_string($question) || ! is_array($options)) {
            return $decoded;
        }

        return [[
            'question' => $question,
            'options' => array_values(array_filter(array_map(static fn ($option): string => is_string($option) ? $option : '', $options))),
            'answer' => is_int($answer) ? $answer : 0,
        ]];
    }
}
