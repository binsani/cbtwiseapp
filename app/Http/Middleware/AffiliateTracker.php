<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\AffiliateClick;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;

class AffiliateTracker
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->has('ref')) {
            $ref = $request->query('ref');
            
            $referrer = User::where('referral_code', $ref)->first();
            
            if ($referrer && $referrer->affiliate && $referrer->affiliate->isActive()) {
                $affiliate = $referrer->affiliate;
                
                // Do not track self-referral clicks if user is logged in as the affiliate
                if ($request->user() && $request->user()->id === $referrer->id) {
                    return $next($request);
                }
                
                $cookieToken = $request->cookie('cbtwise_aff_token') ?: Str::random(40);
                
                AffiliateClick::create([
                    'affiliate_id'  => $affiliate->id,
                    'ip'            => $request->ip(),
                    'cookie_token'  => $cookieToken,
                    'referral_code' => $ref,
                    'landing_url'   => $request->fullUrl(),
                    'user_agent'    => $request->userAgent(),
                    'clicked_at'    => now(),
                ]);
                
                $response = $next($request);
                
                $cookieDays = config('cbtwise_phase5.affiliate_cookie_days', 30);
                return $response->withCookie(cookie()->make('cbtwise_aff_token', $cookieToken, $cookieDays * 24 * 60));
            }
        }
        
        return $next($request);
    }
}
