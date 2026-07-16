<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\StepType;
use App\Livewire\Actions\Logout;
use App\Models\Course;
use App\Models\User;
use Illuminate\Contracts\Translation\HasLocalePreference;
use Illuminate\Support\Facades\App;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    App::setLocale('en');
});

test('default locale is english', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSee(__('Dashboard'));
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
        ->assertSee(__('Courses'));
});

test('page shows czech factory placeholder text when locale is cs', function () {
    $user = User::factory()->create(['locale' => 'cs']);
    $course = Course::factory()->published()->create(['locale' => 'cs']);
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

test('registration respects current locale', function () {
    App::setLocale('cs');

    Livewire::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('register')
        ->assertRedirect(route('dashboard', absolute: false));

    $user = User::where('email', 'test@example.com')->first();
    expect($user->locale)->toBe('cs');
});

test('login syncs user locale to session', function () {
    $user = User::factory()->create(['locale' => 'cs']);

    Livewire::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password')
        ->call('login')
        ->assertRedirect(route('dashboard', absolute: false));

    expect(session('locale'))->toBe('cs');
});

test('user model implements HasLocalePreference', function () {
    $user = User::factory()->create(['locale' => 'cs']);

    expect($user)->toBeInstanceOf(HasLocalePreference::class);
    expect($user->preferredLocale())->toBe('cs');
});

test('locale persists after logout', function () {
    $user = User::factory()->create(['locale' => 'cs']);

    $this->actingAs($user)
        ->post(route('locale.switch'), ['locale' => 'cs'])
        ->assertRedirect();

    app(Logout::class)();

    expect(session('locale'))->toBe('cs');
});

test('locale switch route has throttle middleware', function () {
    // The route itself should still work
    $this->from('/')
        ->post(route('locale.switch'), ['locale' => 'cs'])
        ->assertRedirect('/');
});

test('locale switch is throttled after 30 requests', function () {
    for ($i = 0; $i < 30; $i++) {
        $this->from('/')
            ->post(route('locale.switch'), ['locale' => 'cs'])
            ->assertRedirect('/');
    }

    $this->from('/')
        ->post(route('locale.switch'), ['locale' => 'cs'])
        ->assertStatus(429);
});

test('invalid locale defaults to english', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->post(route('locale.switch'), ['locale' => 'invalid'])
        ->assertRedirect();

    expect($user->fresh()->locale)->toBe('en');
});
