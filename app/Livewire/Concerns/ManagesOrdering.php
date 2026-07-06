<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

trait ManagesOrdering
{
    public function resetPageOnSearch(): void
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

        $modelClass = $item::class;

        $query = $modelClass::where('order', '<', $item->getAttribute('order'));
        if ($parentFk !== null) {
            $query->where($parentFk, $item->getAttribute($parentFk));
        }
        $previous = $query->orderBy('order', 'desc')->first();

        if ($previous === null) {
            return;
        }

        $itemOrder = $item->getAttribute('order');
        $previousOrder = $previous->getAttribute('order');

        DB::transaction(function () use ($item, $itemOrder, $previous, $previousOrder): void {
            $previous->update(['order' => -1]);
            $item->update(['order' => $previousOrder]);
            $previous->update(['order' => $itemOrder]);
        });
    }

    protected function moveItemDown(Model $item, ?string $parentFk = null): void
    {
        $this->authorize('update', $item);

        $modelClass = $item::class;

        $query = $modelClass::where('order', '>', $item->getAttribute('order'));
        if ($parentFk !== null) {
            $query->where($parentFk, $item->getAttribute($parentFk));
        }
        $next = $query->orderBy('order')->first();

        if ($next === null) {
            return;
        }

        $itemOrder = $item->getAttribute('order');
        $nextOrder = $next->getAttribute('order');

        DB::transaction(function () use ($item, $itemOrder, $next, $nextOrder): void {
            $next->update(['order' => -1]);
            $item->update(['order' => $nextOrder]);
            $next->update(['order' => $itemOrder]);
        });
    }
}
