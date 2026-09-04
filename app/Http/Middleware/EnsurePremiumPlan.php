<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePremiumPlan
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->isPremium()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'This feature requires a Premium subscription.'], 403);
            }
            return redirect()->route('pricing')->with('error', 'This feature requires a Premium subscription.');
        }

        return $next($request);
    }
}
