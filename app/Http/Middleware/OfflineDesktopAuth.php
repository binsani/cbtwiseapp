<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OfflineDesktopAuth
{
    /**
     * In offline standalone desktop mode (e.g. localhost/127.0.0.1 or APP_OFFLINE_DESKTOP=true),
     * automatically authenticate a local user so all dashboard pages, practice screens,
     * history, analytics, and exams work seamlessly without requiring internet/login barriers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $isLocalEngine = in_array($request->getHost(), ['127.0.0.1', 'localhost']) || env('APP_OFFLINE_DESKTOP', false);

        if ($isLocalEngine && !Auth::check()) {
            // Find existing local candidate or default user
            $user = User::where('email', 'student@cbtwise.com')
                ->orWhere('email', 'admin@cbtwise.com.ng')
                ->first() ?? User::first();

            if (!$user) {
                // Seed a dedicated offline practice student if none exists
                $user = User::create([
                    'name' => 'Offline Candidate',
                    'email' => 'student@cbtwise.com',
                    'password' => bcrypt('cbtwise123'),
                    'phone' => '08000000000',
                    'state' => 'Abuja',
                    'school' => 'CBTwise Academy',
                    'exam_year' => now()->year,
                    'plan' => 'premium', // Grant full access offline
                    'email_verified_at' => now(),
                ]);
            }

            // Ensure user has verified email and full practice capabilities offline
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user, remember: true);
        }

        return $next($request);
    }
}
