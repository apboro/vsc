<?php

namespace App\Http\Controllers\API\Letters;

use App\Http\APIResponse;
use App\Http\Controllers\ApiController;
use App\Http\Requests\APIListRequest;
use App\Models\Dictionaries\Letters;
use App\Models\Dictionaries\PatternLetters;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;

class LettersListController extends ApiController
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
        $patterns = PatternLetters::queryRaw()
            ->where(['enabled' => true])
            ->select(['id', 'name'])
            ->orderBy('order')
            ->get();

        $query = Letters::queryRaw()
            ->tap(new ForOrganization($request->input('organization_id'), true))
            ->orderBy('id');

        $patternIDs = $query->get()->unique('pattern_id')->pluck('pattern_id')->toArray();

        return APIResponse::response([
            'patterns' => $patterns,
            'patternIDs' => $patternIDs
        ], []);
    }
}
