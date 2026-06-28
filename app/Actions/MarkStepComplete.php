<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Database\QueryException;

class MarkStepComplete
{
    public function handle(User $user, Step $step): bool
    {
        try {
            StepCompletion::create([
                'user_id' => $user->id,
                'step_id' => $step->id,
                'completed_at' => now(),
            ]);

            return true;
        } catch (QueryException) {
            return false;
        }
    }
}
