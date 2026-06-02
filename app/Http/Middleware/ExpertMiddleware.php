<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpertMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $role = $user->role ?? 'user';

        if (!$user || !in_array($role, ['admin', 'expert'], true)) {
            return redirect()
                ->route('home')
                ->with('error', 'Недостаточно прав для доступа к этой странице.');
        }

        return $next($request);
    }
}

