<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TriviaAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TriviaAttempt> */
class TriviaAttemptFactory extends Factory
{
    protected $model = TriviaAttempt::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'score' => 0,
            'total' => 10,
            'answers' => '[]',
            'completed_at' => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completed_at' => now(),
            'score' => fake()->numberBetween(0, $attributes['total']),
        ]);
    }
}
