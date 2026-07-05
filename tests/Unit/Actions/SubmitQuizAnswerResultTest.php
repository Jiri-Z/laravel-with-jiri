<?php

use App\Actions\SubmitQuizAnswerResult;

test('dto stores isCorrect result', function () {
    $result = new SubmitQuizAnswerResult(isCorrect: true, answer: 'Paris');

    expect($result->isCorrect)->toBeTrue();
});

test('dto stores answer value', function () {
    $result = new SubmitQuizAnswerResult(isCorrect: false, answer: 'London');

    expect($result->answer)->toBe('London');
});

test('dto properties are read-only', function () {
    $result = new SubmitQuizAnswerResult(isCorrect: true, answer: 'Paris');

    expect(fn () => $result->isCorrect = false)->toThrow(Error::class);
});
