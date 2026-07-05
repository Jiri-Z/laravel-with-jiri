<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @method static Builder<TModel> ordered()
 */
trait HasOrder
{
    /**
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}
