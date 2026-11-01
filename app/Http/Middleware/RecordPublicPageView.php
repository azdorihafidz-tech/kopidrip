<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RecordPublicPageView
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        $path = '/'.ltrim($request->path(), '/');
        $isExcluded = $path === '/admin' || str_starts_with($path, '/admin/')
            || in_array($path, ['/cart', '/checkout', '/cart-count', '/webhooks/manual-payment'], true)
            || str_starts_with($path, '/orders/');

        if ($request->isMethod('GET') && ! $isExcluded && $response->isSuccessful() && str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            $campaign = [];
            foreach (['utm_source', 'utm_medium', 'utm_campaign', 'utm_id', 'utm_content', 'utm_term'] as $key) {
                $value = $request->query($key);
                if (is_string($value) && preg_match('/^[A-Za-z0-9_. -]{1,100}$/', $value)) {
                    $campaign[$key] = $value;
                }
            }

            $referrerHost = null;
            $referer = $request->header('referer');
            if (is_string($referer) && filter_var($referer, FILTER_VALIDATE_URL)) {
                $host = parse_url($referer, PHP_URL_HOST);
                $referrerHost = is_string($host) ? substr($host, 0, 190) : null;
            }

            $countryCode = strtoupper((string) $request->header('CF-IPCountry', ''));
            if (! preg_match('/^[A-Z]{2}$/', $countryCode) || in_array($countryCode, ['XX', 'T1'], true)) {
                $countryCode = null;
            }

            DB::table('analytics_events')->insert([
                'event_name' => 'page_view', 'path' => substr($path, 0, 255),
                'referrer_host' => $referrerHost,
                'visitor_key' => hash('sha256', $request->session()->getId().config('app.key')),
                'country_code' => $countryCode,
                'campaign' => $campaign === [] ? null : json_encode($campaign, JSON_THROW_ON_ERROR),
                'created_at' => now(),
            ]);
        }

        return $response;
    }
}
