<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;

use Closure;
use Auth;

class TeamsPermission {

    public function handle(Request $request, Closure $next): mixed {
        $guard = BKND;

        if (Auth::guard($guard)->check()) {
            // session value set on login
            setPermissionsTeamId(Auth::guard($guard)->user()->team_id);
        }

        return $next($request);
    }
}
