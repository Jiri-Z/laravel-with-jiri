<?php

use App\Exceptions\NotEnrolledException;
use App\Exceptions\OrphanedStepException;
use App\Exceptions\StepNotAccessibleException;

test('orphaned step exception message', function () {
    $e = new OrphanedStepException;

    expect($e->getMessage())->toBe(__('exceptions.orphaned_step'));
});

test('not enrolled exception message', function () {
    $e = new NotEnrolledException;

    expect($e->getMessage())->toBe(__('exceptions.not_enrolled'));
});

test('step not accessible exception message', function () {
    $e = new StepNotAccessibleException;

    expect($e->getMessage())->toBe(__('exceptions.step_not_accessible'));
});

test('all domain exceptions extend exception', function () {
    expect(new NotEnrolledException)->toBeInstanceOf(Exception::class)
        ->and(new OrphanedStepException)->toBeInstanceOf(Exception::class)
        ->and(new StepNotAccessibleException)->toBeInstanceOf(Exception::class);
});
