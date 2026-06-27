<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Step>
 */
class StepFactory extends Factory
{
    protected $model = Step::class;

    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'title' => fake()->sentence(3),
            'type' => fake()->randomElement(['reading', 'quiz_single', 'quiz_multiple', 'quiz_text', 'coding']),
            'content' => fake()->paragraphs(3, true),
            'order' => fake()->numberBetween(0, 100),
        ];
    }

    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'reading',
            'content' => fake()->paragraphs(5, true),
        ]);
    }
}
