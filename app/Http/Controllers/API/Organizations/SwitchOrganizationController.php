<?php

namespace App\Http\Controllers\API\Organizations;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Organization\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SwitchOrganizationController extends ApiController
{
    /**
     * Get organizations list user can switch to.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $organizations = Organization::query()->select(['id', 'title'])->get();

        $current = Current::get($request);

        return APIResponse::response([
            'organizations' => $organizations,
            'current' => $current->organizationId(),
        ]);
    }

    /**
     * Switch to other organizations.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function switch(Request $request): JsonResponse
    {
        /** @var Organization|null $organization */
        $organization = Organization::query()->select(['id', 'title'])->where('id', $request->input('id'))->first();

        if ($organization === null) {
            return APIResponse::error('Организация не найдена');
        }

        $current = Current::get($request);

        return APIResponse::success();//->withCookie($current->organizationToCookie($organization->id));
    }
}
