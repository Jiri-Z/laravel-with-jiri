<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! ($request->user()->isAdmin() || $request->user()->isInstructor())) {
            abort(403);
        }

        return $next($request);
    }
}
