<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Http\Middleware\SetLocale;
use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

uses(TestCase::class);

test('set locale middleware runs after start session', function () {
    $middlewareGroups = app(Kernel::class)->getMiddlewareGroups();
    $web = $middlewareGroups['web'] ?? [];

    $startSessionIndex = array_search(StartSession::class, $web, true);
    $setLocaleIndex = array_search(SetLocale::class, $web, true);

    expect($startSessionIndex)->toBeLessThan($setLocaleIndex);
});

test('locale is applied on request after switching via post', function () {
    $user = User::factory()->create(['locale' => 'en']);

    $this->actingAs($user)
        ->from('/courses')
        ->post(route('locale.switch'), ['locale' => 'cs'])
        ->assertRedirect('/courses');

    // Verify the subsequent request renders with cs locale
    $response = $this->actingAs($user)->get('/courses');
    $response->assertOk();

    expect(App::getLocale())->toBe('cs');
});
