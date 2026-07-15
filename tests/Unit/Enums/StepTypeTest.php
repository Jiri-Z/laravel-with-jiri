<?php

use App\Enums\StepType;

test('step type has two cases', function () {
    expect(StepType::cases())->toHaveCount(2);
});

test('step type values match expected strings', function () {
    expect(StepType::Reading->value)->toBe('reading')
        ->and(StepType::Quiz->value)->toBe('quiz');
});

test('step type from value returns correct case', function () {
    expect(StepType::from('reading'))->toBe(StepType::Reading)
        ->and(StepType::from('quiz'))->toBe(StepType::Quiz);
});

test('step type invalid value throws', function () {
    expect(fn () => StepType::from('invalid'))->toThrow(ValueError::class);
});
