<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder<static> ordered()
 */
trait HasOrder
{
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
