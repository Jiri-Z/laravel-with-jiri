<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $locale = $user !== null ? $user->locale : null;
        $locale ??= $request->hasSession() ? $request->session()->get('locale') : null;
        $locale ??= config('app.locale');

        App::setLocale($locale);

        return $next($request);
    }
}
