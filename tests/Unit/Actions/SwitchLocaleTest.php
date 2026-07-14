<?php

declare(strict_types=1);

namespace Tests\Unit\Actions;

use App\Actions\SwitchLocale;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SwitchLocaleTest extends TestCase
{
    private SwitchLocale $switcher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->switcher = new SwitchLocale;
    }

    public function test_switches_to_czech_for_authenticated_user(): void
    {
        $user = User::factory()->create(['locale' => 'en']);

        $this->actingAs($user);
        $this->switcher->handle('cs');

        $this->assertEquals('cs', $user->fresh()->locale);
        $this->assertEquals('cs', Session::get('locale'));
        $this->assertEquals('cs', App::getLocale());
    }

    public function test_switches_to_english_for_authenticated_user(): void
    {
        $user = User::factory()->create(['locale' => 'cs']);

        $this->actingAs($user);
        $this->switcher->handle('en');

        $this->assertEquals('en', $user->fresh()->locale);
        $this->assertEquals('en', Session::get('locale'));
        $this->assertEquals('en', App::getLocale());
    }

    public function test_switches_locale_for_guest_via_session(): void
    {
        $this->switcher->handle('cs');

        $this->assertNull(auth()->user());
        $this->assertEquals('cs', Session::get('locale'));
        $this->assertEquals('cs', App::getLocale());
    }

    public function test_invalid_locale_is_ignored(): void
    {
        App::setLocale('en');
        Session::put('locale', 'en');

        $this->expectException(ValidationException::class);
        $this->switcher->handle('de');
    }

    public function test_updates_session_even_when_invalid_locale_is_skipped(): void
    {
        App::setLocale('en');
        Session::put('locale', 'en');

        $this->switcher->handle('cs');

        $this->assertEquals('cs', Session::get('locale'));
    }

    public function test_accepts_explicit_user_parameter(): void
    {
        $user = User::factory()->create(['locale' => 'en']);

        $this->switcher->handle('cs', $user);

        $this->assertEquals('cs', $user->fresh()->locale);
        $this->assertEquals('cs', Session::get('locale'));
        $this->assertEquals('cs', App::getLocale());
        $this->assertNull(auth()->user());
    }
}
