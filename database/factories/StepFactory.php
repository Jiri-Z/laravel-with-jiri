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
            'reading_content' => __('factories.step_reading_default'),
            'order' => fake()->numberBetween(1, 1000),
            'published' => true,
        ];
    }

    public function reading(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Reading,
            'reading_content' => __('factories.step_reading_content'),
        ]);
    }

    public function quizSingle(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                [
                    'type' => 'single',
                    'question' => __('factories.quiz_single_question'),
                    'options' => __('factories.quiz_single_options'),
                    'answer' => 1,
                    'explanation' => __('factories.quiz_single_explanation'),
                    'difficulty' => 'easy',
                    'topic' => 'general',
                ],
            ]),
        ]);
    }

    public function quizMultiple(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                [
                    'type' => 'multiple',
                    'question' => __('factories.quiz_multiple_question'),
                    'options' => __('factories.quiz_multiple_options'),
                    'answer' => [0, 3],
                    'explanation' => __('factories.quiz_multiple_explanation'),
                    'difficulty' => 'easy',
                    'topic' => 'general',
                ],
            ]),
        ]);
    }

    public function quizText(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                [
                    'type' => 'text',
                    'question' => __('factories.quiz_text_question'),
                    'answer' => __('factories.quiz_text_answer'),
                    'alternatives' => null,
                    'explanation' => __('factories.quiz_text_explanation'),
                    'difficulty' => 'easy',
                    'topic' => 'general',
                ],
            ]),
        ]);
    }

    public function quiz(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => StepType::Quiz,
            'quiz_content' => json_encode([
                [
                    'type' => 'single',
                    'question' => __('factories.quiz_single_question'),
                    'options' => __('factories.quiz_single_options'),
                    'answer' => 1,
                    'explanation' => __('factories.quiz_single_explanation'),
                    'difficulty' => 'easy',
                    'topic' => 'general',
                ],
                [
                    'type' => 'text',
                    'question' => __('factories.quiz_text_question'),
                    'answer' => __('factories.quiz_text_answer'),
                    'alternatives' => null,
                    'explanation' => __('factories.quiz_text_explanation'),
                    'difficulty' => 'easy',
                    'topic' => 'general',
                ],
                [
                    'type' => 'multiple',
                    'question' => __('factories.quiz_multiple_question'),
                    'options' => __('factories.quiz_multiple_options'),
                    'answer' => [0, 3],
                    'explanation' => __('factories.quiz_multiple_explanation'),
                    'difficulty' => 'easy',
                    'topic' => 'general',
                ],
            ]),
        ]);
    }
}
