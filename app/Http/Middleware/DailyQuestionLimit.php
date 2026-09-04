<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DailyQuestionLimit
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->hasReachedDailyLimit()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You have reached your free daily practice limit.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'You have reached your free daily practice limit. Please upgrade to Premium for unlimited practice.');
        }

        return $next($request);
    }
}
