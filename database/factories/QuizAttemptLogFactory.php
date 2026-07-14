<?php

namespace Database\Factories;

use App\Models\QuizAttemptLog;
use App\Models\Step;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<QuizAttemptLog>
 */
class QuizAttemptLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'step_id' => Step::factory(),
            'score' => fake()->numberBetween(0, 10),
            'total' => fake()->numberBetween(1, 10),
            'answers' => [],
            'attempted_at' => fake()->dateTimeThisMonth(),
        ];
    }
}
