<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Repl;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('guest is redirected to login', function () {
    $this->get('/repl')->assertRedirect('/login');
});

test('page loads for authenticated user', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/repl')
        ->assertOk()
        ->assertSeeLivewire(Repl::class);
});

test('shows editor container and run button', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertOk()
        ->assertSeeHtml('editor-container')
        ->assertSeeHtml('@click="run()"');
});

test('shows reset button', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('@click="resetCode()"');
});

test('shows keyboard shortcut hint', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('text-xs text-gray-400');
});

test('shows loading indicator initially', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('animate-pulse');
});

test('shows loading php runtime indicator', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('loading-php');
});

test('shows php runtime ready indicator', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('text-green-500');
});

test('shows php runtime failed indicator', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('text-red-500');
});
