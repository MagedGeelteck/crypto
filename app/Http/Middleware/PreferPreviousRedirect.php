<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;

class PreferPreviousRedirect
{
    /**
     * If a controller returned a RedirectResponse to the home route, prefer
     * redirecting to the session previous URL when it exists and is not home.
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        try {
            $homeUrl = route('home');
        } catch (\Exception $e) {
            $homeUrl = url('/');
        }

        if ($response instanceof RedirectResponse) {
            $target = $response->getTargetUrl();

            // determine a reasonable previous url: prefer session _previous, fallback to referer
            $previous = session('_previous.url') ?? $request->headers->get('referer');

            if ($previous && $target && $this->isSameUrl($target, $homeUrl) && !$this->isSameUrl($previous, $homeUrl)) {
                // preserve flash data by mutating the existing RedirectResponse
                $response->setTargetUrl($previous);
            }
        }

        return $response;
    }

    protected function isSameUrl($a, $b)
    {
        if (!$a || !$b) return false;
        return rtrim($a, '/') === rtrim($b, '/');
    }
}
