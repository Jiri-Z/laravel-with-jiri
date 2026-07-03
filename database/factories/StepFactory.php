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
            'type' => StepType::Reading,
            'content' => __('factories.step_reading_default'),
            'order' => fake()->numberBetween(1, 1000),
            'published' => true,
        ];
    }

    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Reading,
            'content' => __('factories.step_reading_content'),
        ]);
    }

    public function quizSingle(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'content' => json_encode([
                [
                    'type' => 'single',
                    'question' => __('factories.quiz_single_question'),
                    'options' => __('factories.quiz_single_options'),
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
                    'question' => __('factories.quiz_multiple_question'),
                    'options' => __('factories.quiz_multiple_options'),
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
                    'question' => __('factories.quiz_text_question'),
                    'correct_answer' => __('factories.quiz_text_answer'),
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
                    'question' => __('factories.quiz_single_question'),
                    'options' => __('factories.quiz_single_options'),
                    'correct_answer' => 1,
                ],
                [
                    'type' => 'text',
                    'question' => __('factories.quiz_text_question'),
                    'correct_answer' => __('factories.quiz_text_answer'),
                ],
                [
                    'type' => 'multiple',
                    'question' => __('factories.quiz_multiple_question'),
                    'options' => __('factories.quiz_multiple_options'),
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
                'prompt' => __('factories.coding_prompt'),
                'initial_code' => __('factories.coding_initial_code'),
                'test_code' => __('factories.coding_test_code'),
                'expected_output' => __('factories.coding_expected_output'),
            ]),
        ]);
    }
}
