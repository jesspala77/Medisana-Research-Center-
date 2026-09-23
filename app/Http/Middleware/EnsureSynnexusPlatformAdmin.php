<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSynnexusPlatformAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_if(! $user, 403);
        abort_if(! $user->isPlatformAdmin(), 403);

        return $next($request);
    }
}