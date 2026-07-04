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
 *
 * @mixin IdeHelperStep
 */
#[Fillable(['lesson_id', 'title', 'type', 'content', 'order', 'published'])]
class Step extends Model
{
    /** @use HasFactory<StepFactory> */
    use HasFactory;

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
    protected function search(Builder $query, string $term): Builder
    {
        if (mb_strlen($term) < 2) {
            return $query;
        }

        return $query->where(fn (Builder $q): Builder => $q->where('title', 'like', "%{$term}%"));
    }

    public function isAccessibleBy(User $user): bool
    {
        return $this->lesson->hasUserCompletedPreviousStep($user, $this);
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
        if (empty($this->content) || ! json_validate($this->content)) {
            return null;
        }

        return json_decode($this->content, true);
    }

    /** @return array{prompt: string, initial_code: string, test_code: string, expected_output: string} */
    public function getCodingData(): array
    {
        $data = $this->getContentAsArray();

        if (! is_array($data)) {
            return ['prompt' => '', 'initial_code' => '', 'test_code' => '', 'expected_output' => ''];
        }

        return [
            'prompt' => $data['prompt'] ?? '',
            'initial_code' => $data['initial_code'] ?? '',
            'test_code' => $data['test_code'] ?? '',
            'expected_output' => $data['expected_output'] ?? '',
        ];
    }
}
