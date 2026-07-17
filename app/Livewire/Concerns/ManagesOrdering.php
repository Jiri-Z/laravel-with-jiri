<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait ManagesOrdering
{
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function deleteItem(Model $item): void
    {
        $this->authorize('delete', $item);
        $item->delete();
    }

    protected function moveItemUp(Model $item, ?string $parentFk = null): void
    {
        $this->authorize('update', $item);

        $itemOrder = $item->getRawOriginal('order');
        $modelClass = $item::class;

        if ($itemOrder === null || (! is_int($itemOrder) && ! is_numeric($itemOrder))) {
            return;
        }

        $query = $modelClass::where('order', '<', $itemOrder);
        if ($parentFk !== null) {
            $query->where($parentFk, $item->getAttribute($parentFk));
        }
        $previous = $query->orderBy('order', 'desc')->first();

        if ($previous === null) {
            return;
        }

        $previousOrder = $previous->getAttribute('order');

        if (! is_int($previousOrder) && ! is_numeric($previousOrder)) {
            return;
        }

        $itemOrderValue = (int) $itemOrder;
        $previousOrderValue = (int) $previousOrder;

        DB::transaction(function () use ($item, $itemOrderValue, $previous, $previousOrderValue): void {
            $previous->update(['order' => -1]);
            $item->update(['order' => $previousOrderValue]);
            $previous->update(['order' => $itemOrderValue]);
        });
    }

    protected function moveItemDown(Model $item, ?string $parentFk = null): void
    {
        $this->authorize('update', $item);

        $itemOrder = $item->getRawOriginal('order');
        $modelClass = $item::class;

        if (! is_int($itemOrder) && ! is_numeric($itemOrder)) {
            return;
        }

        $query = $modelClass::where('order', '>', $itemOrder);
        if ($parentFk !== null) {
            $query->where($parentFk, $item->getAttribute($parentFk));
        }
        $next = $query->orderBy('order')->first();

        if ($next === null) {
            return;
        }

        $nextOrder = $next->getAttribute('order');

        if (! is_int($nextOrder) && ! is_numeric($nextOrder)) {
            return;
        }

        $itemOrderValue = (int) $itemOrder;
        $nextOrderValue = (int) $nextOrder;

        DB::transaction(function () use ($item, $itemOrderValue, $next, $nextOrderValue): void {
            $next->update(['order' => -1]);
            $item->update(['order' => $nextOrderValue]);
            $next->update(['order' => $itemOrderValue]);
        });
    }
}
