<?php

namespace App\Http\Controllers\API\Organizations;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\OrganizationStatus;
use App\Models\Organization\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationListController extends ApiController
{
    /**
     * Get organizations list.
     *
     * @param ApiListRequest $request
     *
     * @return  JsonResponse
     */
    public function list(ApiListRequest $request): JsonResponse
    {
        $query = Organization::query()
            ->with(['info'])
            ->orderBy('id');

        // current page automatically resolved from request via `page` parameter
        $organizations = $query->paginate($request->perPage(10));

        /** @var LengthAwarePaginator $organizations */
        $organizations->transform(function (Organization $organization) {
            return [
                'id' => $organization->id,
                'title' => $organization->title,
                'active' => $organization->hasStatus(OrganizationStatus::active),
            ];
        });

        return APIResponse::list($organizations, ['ID', 'Название']);
    }
}
