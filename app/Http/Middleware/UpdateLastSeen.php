<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $lastUpdateTs = (int) $request->session()->get('last_seen_update_ts', 0);
            $nowTs = now()->timestamp;

            // Avoid writing on every request.
            if ($nowTs - $lastUpdateTs >= 60) {
                auth()->user()->forceFill(['last_seen_at' => now()])->saveQuietly();
                $request->session()->put('last_seen_update_ts', $nowTs);
            }
        }

        return $next($request);
    }
}
