<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class SwitchLocale
{
    public function handle(string $locale, ?User $user = null): void
    {
        if (! in_array($locale, ['en', 'cs'])) {
            throw ValidationException::withMessages([
                'locale' => 'The selected locale is invalid.',
            ]);
        }

        $user ??= auth()->user();

        if ($user !== null) {
            $user->update(['locale' => $locale]);
        }

        session(['locale' => $locale]);
        App::setLocale($locale);
    }
}
