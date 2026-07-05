<?php

use App\Enums\StepType;

test('step type has three cases', function () {
    expect(StepType::cases())->toHaveCount(3);
});

test('step type values match expected strings', function () {
    expect(StepType::Reading->value)->toBe('reading')
        ->and(StepType::Quiz->value)->toBe('quiz')
        ->and(StepType::Coding->value)->toBe('coding');
});

test('step type from value returns correct case', function () {
    expect(StepType::from('reading'))->toBe(StepType::Reading)
        ->and(StepType::from('quiz'))->toBe(StepType::Quiz)
        ->and(StepType::from('coding'))->toBe(StepType::Coding);
});

test('step type invalid value throws', function () {
    expect(fn () => StepType::from('invalid'))->toThrow(ValueError::class);
});
