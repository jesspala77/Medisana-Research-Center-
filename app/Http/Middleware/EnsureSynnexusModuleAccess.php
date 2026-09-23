<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSynnexusModuleAccess
{
    public function handle(Request $request, Closure $next, ?string $module = null): Response
    {
        $user = $request->user();

        abort_if(! $user, 403);

        $moduleKey = $module ?: (string) $request->route('module');

        abort_if($moduleKey === '', 403);
        abort_if(! $user->canAccessModule($moduleKey), 403);

        return $next($request);
    }
}