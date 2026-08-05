<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     * Usage in routes: ->middleware('role:super_admin,admin')
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'غير مسموح');
        }

        // If no roles provided, allow
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user's role is in allowed roles
        if (! in_array($user->role, $roles)) {
            abort(403, 'ليس لديك صلاحيات كافية');
        }

        return $next($request);
    }
}
