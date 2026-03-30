<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsModerator
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && ! $user->is_admin
            && $user->can_manage_documents
            && $user->parcours_id !== null
        ) {
            return $next($request);
        }

        return redirect()
            ->route('dashboard')
            ->with('error', 'Acces reserve aux moderateurs de parcours.');
    }
}
