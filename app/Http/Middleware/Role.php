<?php

namespace App\Http\Middleware;

use App\Http\APIResponse;
use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Check role ability.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$roles
     *
     * @return  mixed
     *
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (empty($roles)) {
            return APIResponse::forbidden();
        }

        /** @var User $user */
        $user = $request->user();

        foreach ($roles as $role) {
            if ($user->position->hasRole($role)) {
                return $next($request);
            }
        }

        return APIResponse::forbidden();
    }
}
