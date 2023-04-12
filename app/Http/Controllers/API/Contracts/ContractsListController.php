<?php

namespace App\Http\Controllers\API\Contracts;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\Contracts;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ContractsListController extends ApiController
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
        $current = Current::get($request);
        $query = Contracts::tap(new ForOrganization($current->organizationId(), true))
            ->orderBy('id');

        // current page automatically resolved from request via `page` parameter
        $contracts = $query->paginate($request->perPage());

        /** @var LengthAwarePaginator $contracts */
        $contracts->transform(function (Contracts $contract) {
            return [
                'id' => $contract->id,
                'title' => $contract->name,
            ];
        });

        return APIResponse::list($contracts, ['ID', 'Название']);
    }
}
