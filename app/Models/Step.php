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
#[Fillable(['lesson_id', 'title', 'type', 'reading_content', 'quiz_content', 'coding_content', 'order', 'published'])]
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

        return $query->where(fn (Builder $q): Builder => $q->where('title', 'like', "%{$term}%"));
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

        return is_array($decoded) ? $decoded : null;
    }

    /** @return array{prompt: string, initial_code: string, test_code: string, expected_output: string} */
    public function getCodingData(): array
    {
        $source = $this->coding_content;

        $data = null;
        if (! empty($source) && json_validate($source)) {
            $decoded = json_decode($source, true);

            if (is_array($decoded)) {
                $data = $decoded;
            }
        }

        if (! is_array($data)) {
            return ['prompt' => '', 'initial_code' => '', 'test_code' => '', 'expected_output' => ''];
        }

        $prompt = $data['prompt'] ?? '';
        $initialCode = $data['initial_code'] ?? '';
        $testCode = $data['test_code'] ?? '';
        $expectedOutput = $data['expected_output'] ?? '';

        return [
            'prompt' => is_string($prompt) ? $prompt : '',
            'initial_code' => is_string($initialCode) ? $initialCode : '',
            'test_code' => is_string($testCode) ? $testCode : '',
            'expected_output' => is_string($expectedOutput) ? $expectedOutput : '',
        ];
    }
}
