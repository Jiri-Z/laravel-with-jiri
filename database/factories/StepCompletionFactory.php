<?php

namespace Database\Factories;

use App\Models\Step;
use App\Models\StepCompletion;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StepCompletion>
 */
class StepCompletionFactory extends Factory
{
    protected $model = StepCompletion::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'step_id' => Step::factory(),
            'completed_at' => now(),
            'unlocked_at' => now(),
        ];
    }
}
