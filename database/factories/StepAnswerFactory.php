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
            'question_index' => 0,
            'answer' => fake()->word(),
            'created_at' => now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (StepAnswer $answer) {
            if (! isset($answer->is_correct)) {
                $answer->is_correct = fake()->boolean();
            }
        });
    }
}
