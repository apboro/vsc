<?php

namespace App\Http\Controllers\API\Roles;

use App\Http\APIResponse;
use App\Http\Controllers\API\CookieKeys;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\PositionStatus;
use App\Models\Permissions\Role;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class RolesListController extends ApiController
{
    protected array $defaultFilters = [
        'active' => true,
    ];

    protected array $rememberFilters = [
        'active',
    ];

    protected string $rememberKey = CookieKeys::roles_list;

    /**
     * Get roles list.
     *
     * @param ApiListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $query = Role::query();

        // apply filters
        if (!empty($filters = $request->filters($this->defaultFilters, $this->rememberFilters, $this->rememberKey)) && array_key_exists('active', $filters)) {
            $query->where('active', $filters['active']);
        }

        // current page automatically resolved from request via `page` parameter
        $roles = $query->paginate($request->perPage(10, $this->rememberKey));

        /** @var LengthAwarePaginator $roles */
        $roles->transform(function (Role $role) {

            return [
                'id' => $role->id,
                'active' => $role->active,
                'locked' => $role->locked,
                'name' => $role->name,
                'description' => $role->description,
            ];
        });

        return APIResponse::list(
            $roles,
            ['Роль', 'Описание'],
            $filters,
            $this->defaultFilters,
            []
        )->withCookie(cookie($this->rememberKey, $request->getToRemember()));
    }
}
