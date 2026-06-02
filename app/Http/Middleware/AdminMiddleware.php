<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || ($user->role ?? 'user') !== 'admin') {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'Это действие доступно только администратору.');
        }

        return $next($request);
    }
}

