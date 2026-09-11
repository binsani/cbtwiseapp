<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        $parsedRoles = [];
        foreach ($roles as $role) {
            foreach (explode('|', (string) $role) as $r) {
                $trimmed = trim($r);
                if ($trimmed !== '') {
                    $parsedRoles[] = $trimmed;
                }
            }
        }

        if (empty($parsedRoles) || !Auth::user()->hasAnyRole($parsedRoles)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
