<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Step;
use App\Models\StepCompletion;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CodingViewer extends Component
{
    public Step $step;

    public bool $completed = false;

    public function mount(): void
    {
        $this->completed = StepCompletion::where('user_id', auth()->id())
            ->where('step_id', $this->step->id)
            ->exists();
    }

    public function markCodingComplete(): void
    {
        if ($this->completed) {
            return;
        }

        StepCompletion::create([
            'user_id' => auth()->id(),
            'step_id' => $this->step->id,
            'completed_at' => now(),
        ]);

        $this->completed = true;
    }

    public function render(): View
    {
        return view('livewire.coding-viewer');
    }
}
