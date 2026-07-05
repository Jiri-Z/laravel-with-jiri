<?php

use App\Models\Concerns\HasOrder;
use App\Models\Course;
use App\Models\Lesson;

test('ordered scope sorts by order column ascending', function () {
    $course = Course::factory()->create();
    $c = Lesson::factory()->create(['course_id' => $course->id, 'order' => 2]);
    $b = Lesson::factory()->create(['course_id' => $course->id, 'order' => 1]);
    $a = Lesson::factory()->create(['course_id' => $course->id, 'order' => 0]);

    $ordered = Lesson::ordered()->get();

    expect($ordered->pluck('id')->toArray())->toEqual([$a->id, $b->id, $c->id]);
});

test('trait is used by orderable models', function () {
    expect(in_array(HasOrder::class, class_uses(Course::class)))->toBeTrue()
        ->and(in_array(HasOrder::class, class_uses(Lesson::class)))->toBeTrue();
});
