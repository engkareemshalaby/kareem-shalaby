<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (Schema::hasTable('visits') && $request->isMethod('GET') && ! $request->is('admin*') && ! Str::contains((string) $response->headers->get('Content-Type'), ['application/xml'])) {
            $agent = (string) $request->userAgent();
            $isBot = preg_match('/bot|crawl|spider|slurp|bingpreview/i', $agent) === 1;

            Visit::create([
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'ip_hash' => hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')),
                'path' => Str::limit('/'.$request->path(), 2048, ''),
                'referrer' => Str::limit((string) $request->headers->get('referer'), 2048, ''),
                'device' => preg_match('/mobile|android|iphone/i', $agent) ? 'mobile' : 'desktop',
                'browser' => $this->browser($agent),
                'platform' => $this->platform($agent),
                'user_agent' => Str::limit($agent, 2000, ''),
                'is_bot' => $isBot,
            ]);
        }

        return $response;
    }

    private function browser(string $agent): string
    {
        return match (true) {
            Str::contains($agent, 'Edg/') => 'Edge',
            Str::contains($agent, 'Chrome/') => 'Chrome',
            Str::contains($agent, 'Firefox/') => 'Firefox',
            Str::contains($agent, 'Safari/') => 'Safari',
            default => 'Other',
        };
    }

    private function platform(string $agent): string
    {
        return match (true) {
            Str::contains($agent, 'Windows') => 'Windows',
            Str::contains($agent, ['iPhone', 'iPad']) => 'iOS',
            Str::contains($agent, 'Android') => 'Android',
            Str::contains($agent, 'Macintosh') => 'macOS',
            Str::contains($agent, 'Linux') => 'Linux',
            default => 'Other',
        };
    }
}
