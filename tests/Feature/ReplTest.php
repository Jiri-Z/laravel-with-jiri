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
        ->assertSee('REPL')
        ->assertSeeHtml('monaco-editor-container');
});
