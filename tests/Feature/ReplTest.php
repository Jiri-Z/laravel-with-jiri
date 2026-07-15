<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Livewire\Repl;
use App\Models\User;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class);

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

test('shows code textarea and run button', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertOk()
        ->assertSeeHtml('x-ref="codeEditor"')
        ->assertSeeHtml('x-on:click="run()"');
});

test('shows reset button', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('x-on:click="resetCode()"');
});

test('shows keyboard shortcut hint', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('Ctrl+Enter');
});

test('shows loading indicator initially', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSeeHtml('animate-pulse');
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

test('shows output placeholder', function () {
    $user = User::factory()->create();

    Livewire::actingAs($user)
        ->test(Repl::class)
        ->assertSee(__('repl.output_placeholder'));
});
