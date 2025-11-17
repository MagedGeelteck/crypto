<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     * Use '*' to trust all proxies (useful behind Tor/reverse proxies).
     *
     * @var array|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    // We'll compute the headers bitmask at runtime in the constructor so
    // this middleware works across multiple framework versions where the
    // header constants may live on different classes. We fall back to the
    // common X-Forwarded bits and, if nothing is available, use a safe
    // default (1|2|4|8 === 15) which corresponds to FOR/host/proto/port.
    protected $headers = 0;

    public function __construct()
    {
        // Prefer the Illuminate Request constant if present (older/newer Laravel)
        if (defined('\Illuminate\\Http\\Request::HEADER_X_FORWARDED_ALL')) {
            $this->headers = \Illuminate\Http\Request::HEADER_X_FORWARDED_ALL;
            return;
        }

        $h = 0;
        if (defined(SymfonyRequest::class . '::HEADER_X_FORWARDED_FOR')) {
            $h |= SymfonyRequest::HEADER_X_FORWARDED_FOR;
        }
        if (defined(SymfonyRequest::class . '::HEADER_X_FORWARDED_HOST')) {
            $h |= SymfonyRequest::HEADER_X_FORWARDED_HOST;
        }
        if (defined(SymfonyRequest::class . '::HEADER_X_FORWARDED_PORT')) {
            $h |= SymfonyRequest::HEADER_X_FORWARDED_PORT;
        }
        if (defined(SymfonyRequest::class . '::HEADER_X_FORWARDED_PROTO')) {
            $h |= SymfonyRequest::HEADER_X_FORWARDED_PROTO;
        }

        // If we were able to compute a bitmask from Symfony constants, use it.
        if ($h !== 0) {
            $this->headers = $h;
            return;
        }

        // Last-resort fallback: common value for FOR/HOST/PROTO/PORT (1|2|4|8)
        $this->headers = 15;
    }
}
