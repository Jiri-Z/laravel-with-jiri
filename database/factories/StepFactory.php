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
            'order' => 0,
        ];
    }

    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'reading',
            'content' => fake()->paragraphs(5, true),
        ]);
    }

    public function quizSingle(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quiz_single',
            'content' => json_encode([
                'question' => 'What is 2+2?',
                'options' => ['3', '4', '5', '6'],
                'correct_answer' => 1,
            ]),
        ]);
    }

    public function quizMultiple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quiz_multiple',
            'content' => json_encode([
                'question' => 'Which are programming languages?',
                'options' => ['Python', 'HTML', 'CSS', 'JavaScript'],
                'correct_answers' => [0, 3],
            ]),
        ]);
    }

    public function quizText(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quiz_text',
            'content' => json_encode([
                'question' => 'What is the capital of France?',
                'correct_answer' => 'Paris',
            ]),
        ]);
    }

    public function coding(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'coding',
            'content' => json_encode([
                'prompt' => 'Write a PHP function that returns the sum of two numbers.',
                'initial_code' => "<?php\n\nfunction add(\$a, \$b) {\n    // Your code here\n}\n",
                'test_code' => "<?php\necho add(2, 3);",
                'expected_output' => '5',
            ]),
        ]);
    }
}
