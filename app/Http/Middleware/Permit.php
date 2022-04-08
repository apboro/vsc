<?php

namespace App\Http\Middleware;

use App\Http\APIResponse;
use App\Models\User\User;
use Closure;
use Illuminate\Http\Request;

class Permit
{
    /**
     * Check access ability.
     *
     * @param Request $request
     * @param Closure $next
     * @param mixed ...$permissions
     *
     * @return  mixed
     *
     */
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (empty($permissions)) {
            return APIResponse::forbidden();
        }

        /** @var User $user */
        $user = $request->user();

        foreach ($permissions as $permission) {
            if ($user->position->can($permission)) {
                return $next($request);
            }
        }

        return APIResponse::forbidden();
    }
}
