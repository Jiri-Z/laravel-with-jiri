<?php

use App\Enums\QuestionType;

test('question type has three cases', function () {
    expect(QuestionType::cases())->toHaveCount(3);
});

test('question type values match expected strings', function () {
    expect(QuestionType::Single->value)->toBe('single')
        ->and(QuestionType::Multiple->value)->toBe('multiple')
        ->and(QuestionType::Text->value)->toBe('text');
});

test('question type from value returns correct case', function () {
    expect(QuestionType::from('single'))->toBe(QuestionType::Single)
        ->and(QuestionType::from('multiple'))->toBe(QuestionType::Multiple)
        ->and(QuestionType::from('text'))->toBe(QuestionType::Text);
});

test('question type invalid value throws', function () {
    expect(fn () => QuestionType::from('invalid'))->toThrow(\ValueError::class);
});
