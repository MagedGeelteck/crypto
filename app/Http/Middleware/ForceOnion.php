<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ForceOnion
{
    /**
     * Redirect to the .onion host when enabled.
     */
    public function handle(Request $request, Closure $next)
    {
        $onionHost = env('ONION_HOST');
        // Normalize ONION_FORCE to boolean (env may return 'true'/'false' strings)
        $force = filter_var(env('ONION_FORCE', false), FILTER_VALIDATE_BOOLEAN);

        // Don't force .onion redirects in local development or on localhost addresses
        if (app()->environment('local') || in_array($request->getHost(), ['localhost', '127.0.0.1', '::1'])) {
            return $next($request);
        }

        if ($force && $onionHost) {
            $currentHost = $request->getHost();

            // Only redirect safe methods to avoid breaking POSTs
            if (! in_array($request->method(), ['GET', 'HEAD'])) {
                return $next($request);
            }

            if ($currentHost !== $onionHost) {
                $uri = $request->getRequestUri();

                // Build target URL. If ONION_HOST already contains scheme, use it as-is.
                if (preg_match('#^https?://#i', $onionHost)) {
                    $target = rtrim($onionHost, '/') . $uri;
                } else {
                    // Default to http for .onion
                    $target = 'http://' . trim($onionHost, '/') . $uri;
                }

                return redirect()->away($target, 302);
            }
        }

        return $next($request);
    }
}
