<?php

namespace Database\Factories;

use App\Enums\StepType;
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
            'type' => fake()->randomElement([StepType::Reading, StepType::Quiz, StepType::Coding]),
            'content' => fake()->paragraphs(3, true),
            'order' => fake()->numberBetween(1, 1000),
        ];
    }

    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Reading,
            'content' => fake()->paragraphs(5, true),
        ]);
    }

    public function quizSingle(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'single',
                    'question' => 'What is 2+2?',
                    'options' => ['3', '4', '5', '6'],
                    'correct_answer' => 1,
                ],
            ]),
        ]);
    }

    public function quizMultiple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'multiple',
                    'question' => 'Which are programming languages?',
                    'options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
                    'correct_answers' => [0, 3],
                ],
            ]),
        ]);
    }

    public function quizText(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'text',
                    'question' => 'What is the capital of France?',
                    'correct_answer' => 'Paris',
                ],
            ]),
        ]);
    }

    public function quiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'single',
                    'question' => 'What is 2+2?',
                    'options' => ['3', '4', '5', '6'],
                    'correct_answer' => 1,
                ],
                [
                    'type' => 'text',
                    'question' => 'What is the capital of France?',
                    'correct_answer' => 'Paris',
                ],
                [
                    'type' => 'multiple',
                    'question' => 'Which are programming languages?',
                    'options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
                    'correct_answers' => [0, 3],
                ],
            ]),
        ]);
    }

    public function coding(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Coding,
            'content' => json_encode([
                'prompt' => 'Write a PHP function that returns the sum of two numbers.',
                'initial_code' => "<?php\n\nfunction add(\$a, \$b) {\n    // Your code here\n}\n",
                'test_code' => "<?php\necho add(2, 3);",
                'expected_output' => '5',
            ]),
        ]);
    }
}
