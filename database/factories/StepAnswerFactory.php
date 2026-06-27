<?php

namespace Database\Factories;

use App\Models\Step;
use App\Models\StepAnswer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StepAnswer>
 */
class StepAnswerFactory extends Factory
{
    protected $model = StepAnswer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'step_id' => Step::factory(),
            'answer' => fake()->word(),
            'is_correct' => fake()->boolean(),
            'created_at' => now(),
        ];
    }
}
