<?php

namespace Tests\Feature\Concerns;

use App\Models\User;

trait AdminTestHelpers
{
    protected function asAdmin(): static
    {
        $this->actingAs(User::factory()->admin()->create());

        return $this;
    }

    protected function asInstructor(): static
    {
        $this->actingAs(User::factory()->create(['role' => 'instructor']));

        return $this;
    }

    protected function asStudent(): static
    {
        $this->actingAs(User::factory()->create(['role' => 'student']));

        return $this;
    }
}
