<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;

class MarkStepComplete
{
    public function handle(User $user, Step $step): bool
    {
        $exists = StepCompletion::where('user_id', $user->id)
            ->where('step_id', $step->id)
            ->exists();

        if ($exists) {
            return false;
        }

        StepCompletion::create([
            'user_id' => $user->id,
            'step_id' => $step->id,
            'completed_at' => now(),
        ]);

        return true;
    }
}
