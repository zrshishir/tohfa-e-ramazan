<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Production sits behind Cloudflare, so every request reaches PHP from a proxy
     * address with the real client details in X-Forwarded-*. With $proxies unset
     * Laravel ignored those headers, decided each request arrived over plain HTTP,
     * and generated http:// redirects — which Cloudflare then bounced back to HTTPS.
     * On the admin login that shows up as a redirect loop.
     *
     * '*' is appropriate here because the origin is only reachable through the proxy;
     * if the server is ever exposed directly, narrow this to Cloudflare's published
     * IP ranges instead.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
