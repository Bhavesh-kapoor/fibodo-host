<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!Auth::user()->hasPermissionTo($permission)) {
            return Response::error('Forbidden', HttpResponse::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
