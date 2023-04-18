<?php

namespace App\Http\Controllers\API\Contracts;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Pattern;
use Illuminate\Http\JsonResponse;

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

        $patterns = Pattern::queryRaw()
            ->where(['enabled' => true])
            ->select(['id', 'name'])
            ->orderBy('order')
            ->get();

        $patternIDs = Contracts::query($current)
            ->orderBy('id')
            ->pluck('pattern_id')
            ->toArray();

        return APIResponse::response([
            'patterns' => $patterns,
            'patternIDs' => $patternIDs,
        ], []);
    }
}
