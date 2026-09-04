<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Legacy XSS protection (belt-and-suspenders for older browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // HSTS — enforce HTTPS for 1 year (only meaningful in production, harmless in dev)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Disable browser features not needed by the app
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=(), payment=()');

        // Content Security Policy
        $appUrl  = rtrim(config('app.url', ''), '/');
        $reverbHost  = config('reverb.servers.reverb.host', 'localhost');
        $reverbPort  = config('reverb.servers.reverb.port', 8080);
        $reverbScheme = config('reverb.servers.reverb.scheme', 'http');
        $wsScheme    = $reverbScheme === 'https' ? 'wss' : 'ws';

        $csp = "default-src 'self'; "
             . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://js.tawk.to https://www.google.com/recaptcha/ https://www.gstatic.com/recaptcha/ https://checkout.paystack.com; "
             . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://fonts.bunny.net; "
             . "font-src 'self' data: https://fonts.gstatic.com https://fonts.bunny.net; "
             . "img-src 'self' data: https://questions.aloc.com.ng https://lh3.googleusercontent.com; "
             . "connect-src 'self' https://api.paystack.co https://api.openai.com {$wsScheme}://{$reverbHost}:{$reverbPort}; "
             . "frame-src 'self' https://checkout.paystack.com https://www.google.com/recaptcha/; "
             . "worker-src 'self' blob:;";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
