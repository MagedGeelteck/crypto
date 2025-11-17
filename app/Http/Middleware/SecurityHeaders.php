<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // If ONION_HOST is set we are serving via .onion — avoid HSTS and similar transport assumptions
        $isOnion = env('ONION_HOST');

        // Conservative CSP that allows inline while we migrate all inline scripts/styles out.
        // After externalizing inline scripts, drop 'unsafe-inline' and consider nonces/hashes.
        $csp = "default-src 'self'; img-src 'self' data:; font-src 'self' data:; style-src 'self' 'unsafe-inline'; script-src 'self' 'unsafe-inline'; connect-src 'self';";

        $response->headers->set('Content-Security-Policy', $csp);
        $response->headers->set('Referrer-Policy', 'no-referrer');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        // Minimal Permissions-Policy (formerly Feature-Policy)
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // Only set HSTS when not serving over .onion (since onion doesn't use TLS the header is irrelevant and may cause issues)
        if (! $isOnion) {
            $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
        }

        return $response;
    }
}
