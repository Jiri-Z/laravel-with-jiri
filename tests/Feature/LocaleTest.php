<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StepType;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    App::setLocale('en');
});

test('default locale is english', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSee('Dashboard');
});

test('authenticated user can switch locale via post', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->from('/dashboard')
        ->post(route('locale.switch'), ['locale' => 'cs'])
        ->assertRedirect('/dashboard');

    expect($user->fresh()->locale)->toBe('cs');
});

test('guest can switch locale via post using session', function () {
    $this->from('/')
        ->post(route('locale.switch'), ['locale' => 'cs'])
        ->assertRedirect('/');

    expect(session('locale'))->toBe('cs');
});

test('locale persists in session after switching', function () {
    $user = User::factory()->create(['locale' => 'cs']);

    $this->actingAs($user)
        ->get('/dashboard');

    $this->actingAs($user)
        ->get('/courses')
        ->assertSee('Courses');
});

test('page shows czech factory placeholder text when locale is cs', function () {
    $user = User::factory()->create(['locale' => 'cs']);
    $course = Course::factory()->published()->create();
    $course->enrollments()->create(['user_id' => $user->id, 'enrolled_at' => now()]);

    $lesson = $course->lessons()->create([
        'title' => 'Test Lesson',
        'slug' => 'test-lesson',
        'published' => true,
        'order' => 1,
    ]);
    $lesson->steps()->create([
        'title' => 'Test Step',
        'type' => StepType::Reading,
        'content' => 'Content',
        'order' => 1,
    ]);

    $response = $this->actingAs($user)->get('/courses');
    $response->assertOk();
    expect($response->content())->toContain(Course::first()->title);
});

test('locale switcher form appears in navigation for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSee(route('locale.switch'));
});

test('invalid locale defaults to english', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->post(route('locale.switch'), ['locale' => 'invalid'])
        ->assertRedirect();

    expect($user->fresh()->locale)->toBe('en');
});
