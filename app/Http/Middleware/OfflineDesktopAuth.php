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
     * In offline standalone desktop mode (bundled with Electron and local SQLite),
     * automatically authenticate a local candidate so all practice screens work offline.
     *
     * Strict Security Controls:
     * 1. Disabled in production environments.
     * 2. Requires config('app.offline_desktop') to be explicitly true.
     * 3. Requires client IP to be local loopback.
     * 4. Authenticates only dedicated offline student, never administrative users.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->isProduction()) {
            return $next($request);
        }

        $isOfflineDesktopEnabled = (bool) config('app.offline_desktop', false);
        $isLoopbackIp = in_array($request->ip(), ['127.0.0.1', '::1'], true);

        if ($isOfflineDesktopEnabled && $isLoopbackIp && !Auth::check()) {
            // Retrieve or provision dedicated offline candidate
            $user = User::where('email', 'student@cbtwise.com')->first();

            if (!$user) {
                // Provision a dedicated offline student profile with standard user role
                $user = User::create([
                    'name' => 'Offline Candidate',
                    'email' => 'student@cbtwise.com',
                    'password' => bcrypt('cbtwise123'),
                    'phone' => '08000000000',
                    'state' => 'Abuja',
                    'school' => 'CBTwise Academy',
                    'exam_year' => now()->year,
                    'plan' => 'premium', // Grant full local practice capabilities
                    'email_verified_at' => now(),
                ]);

                if (method_exists($user, 'assignRole')) {
                    try {
                        $user->assignRole('user');
                    } catch (\Throwable $e) {
                        // Role might not be seeded yet on clean install
                    }
                }
            }

            // Ensure candidate has verified status offline
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
                $user->save();
            }

            Auth::login($user, remember: true);
        }

        return $next($request);
    }
}
