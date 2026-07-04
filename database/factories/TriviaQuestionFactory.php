<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TriviaQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<TriviaQuestion> */
class TriviaQuestionFactory extends Factory
{
    protected $model = TriviaQuestion::class;

    public function definition(): array
    {
        return [
            'topic' => 'routing',
            'type' => 'single',
            'difficulty' => 'easy',
            'question' => fake()->sentence(),
            'options' => json_encode([fake()->word(), fake()->word()]),
            'answer' => fake()->word(),
            'alternatives' => null,
            'explanation' => fake()->sentence(),
            'locale' => 'en',
        ];
    }

    public function multiple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'multiple',
            'options' => json_encode([fake()->word(), fake()->word(), fake()->word()]),
            'answer' => json_encode([0, 1]),
        ]);
    }

    public function text(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'text',
            'options' => null,
            'answer' => fake()->word(),
            'alternatives' => json_encode([fake()->word()]),
        ]);
    }
}
