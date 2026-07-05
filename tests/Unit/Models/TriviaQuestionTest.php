<?php

use App\Models\TriviaQuestion;

test('trivia question has fillable attributes', function () {
    $question = TriviaQuestion::factory()->create([
        'topic' => 'eloquent',
        'type' => 'single',
        'difficulty' => 'hard',
        'question' => 'What is Eloquent?',
        'answer' => 'ORM',
        'explanation' => 'Eloquent is Laravel\'s ORM.',
        'locale' => 'en',
    ]);

    expect($question)
        ->topic->toBe('eloquent')
        ->type->toBe('single')
        ->difficulty->toBe('hard')
        ->question->toBe('What is Eloquent?')
        ->answer->toBe('ORM')
        ->explanation->toBe('Eloquent is Laravel\'s ORM.')
        ->locale->toBe('en');
});

test('trivia question casts options as array', function () {
    $question = TriviaQuestion::factory()->create([
        'options' => ['Option A', 'Option B'],
    ]);

    expect($question->options)->toBe(['Option A', 'Option B']);
});

test('trivia question casts alternatives as array', function () {
    $question = TriviaQuestion::factory()->create([
        'alternatives' => ['alt answer'],
    ]);

    expect($question->alternatives)->toBe(['alt answer']);
});

test('trivia question multiple factory state', function () {
    $question = TriviaQuestion::factory()->multiple()->create();

    expect($question)->type->toBe('multiple');
});

test('trivia question text factory state', function () {
    $question = TriviaQuestion::factory()->text()->create();

    expect($question)->type->toBe('text');
});
