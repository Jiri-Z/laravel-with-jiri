<?php

use App\Exceptions\CourseNotPublishedException;
use App\Exceptions\NotEnrolledException;
use App\Exceptions\StepNotAccessibleException;

test('course not published exception message', function () {
    $e = new CourseNotPublishedException;

    expect($e->getMessage())->toBe(__('exceptions.course_not_published'));
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
    expect(new CourseNotPublishedException)->toBeInstanceOf(Exception::class)
        ->and(new NotEnrolledException)->toBeInstanceOf(Exception::class)
        ->and(new StepNotAccessibleException)->toBeInstanceOf(Exception::class);
});
