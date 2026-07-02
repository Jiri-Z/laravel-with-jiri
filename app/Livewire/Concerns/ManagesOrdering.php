<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

use Illuminate\Support\Facades\DB;

trait ManagesOrdering
{
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    protected function deleteItem(int $id, string $modelClass): void
    {
        $model = $modelClass::findOrFail($id);
        $this->authorize('delete', $model);
        $model->delete();
    }

    protected function moveItemUp(int $id, string $modelClass, ?string $parentFk = null, mixed $parentFkValue = null): void
    {
        $model = $modelClass::findOrFail($id);
        $this->authorize('update', $model);

        $query = $modelClass::where('order', '<', $model->getAttribute('order'));
        if ($parentFk !== null) {
            $query->where($parentFk, $parentFkValue);
        }
        $previous = $query->orderBy('order', 'desc')->first();

        if ($previous === null) {
            return;
        }

        $modelOrder = $model->getAttribute('order');
        $previousOrder = $previous->getAttribute('order');

        DB::transaction(function () use ($model, $modelOrder, $previous, $previousOrder): void {
            $previous->update(['order' => -1]);
            $model->update(['order' => $previousOrder]);
            $previous->update(['order' => $modelOrder]);
        });
    }

    protected function moveItemDown(int $id, string $modelClass, ?string $parentFk = null, mixed $parentFkValue = null): void
    {
        $model = $modelClass::findOrFail($id);
        $this->authorize('update', $model);

        $query = $modelClass::where('order', '>', $model->getAttribute('order'));
        if ($parentFk !== null) {
            $query->where($parentFk, $parentFkValue);
        }
        $next = $query->orderBy('order')->first();

        if ($next === null) {
            return;
        }

        $modelOrder = $model->getAttribute('order');
        $nextOrder = $next->getAttribute('order');

        DB::transaction(function () use ($model, $modelOrder, $next, $nextOrder): void {
            $next->update(['order' => -1]);
            $model->update(['order' => $nextOrder]);
            $next->update(['order' => $modelOrder]);
        });
    }
}
