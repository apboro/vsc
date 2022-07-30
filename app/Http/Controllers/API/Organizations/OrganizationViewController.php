<?php

namespace App\Http\Controllers\API\Organizations;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Models\Dictionaries\OrganizationStatus;
use App\Models\Organization\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationViewController extends ApiController
{
    public function view(Request $request): JsonResponse
    {
        $id = $request->input('id');

        /** @var Organization $organization */
        if ($id === null ||
            null === ($organization = Organization::query()
                ->with(['status', 'info'])
                ->where('id', $id)
                ->first())
        ) {
            return APIResponse::notFound('Организация не найдена');
        }

        $values = [
            'title' => $organization->title,
            'status' => $organization->status->name,
            'active' => $organization->hasStatus(OrganizationStatus::active),
        ];

        return APIResponse::response($values);
    }
}
