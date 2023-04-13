<?php

namespace App\Http\Controllers\API\Contracts;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\Contracts;
use App\Models\Dictionaries\Pattern;
use App\Scopes\ForOrganization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContractsEditController extends ApiEditController
{
    protected array $rules = [
        'name' => 'required',
        'pattern_id' => 'required',
        'organization_id' => 'required',
    ];

    protected array $titles = [
        'name' => 'Название договора',
        'pattern_id' => 'Шаблон',
        'organization_id' => 'Организация',
    ];

    /**
     * Get edit data for the contract.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var Contracts|null $contract */
        $current = Current::get($request);
        if (!isset($request['id']) || $request['id'] === 0) {
            $contract = new Contracts();
        } else {
            $contract = Contracts::where('id', $request['id'])
                ->tap(new ForOrganization($current->organizationId(), true))
                ->first();
        }


        if ($contract === null) {
            return APIResponse::notFound('Организация не найдена');
        }

        // send response
        return APIResponse::form(
            [
                'name' => $contract->name,
                'organization_id' => $contract->organization_id,
                'pattern_id' => $contract->pattern_id,
            ],
            $this->rules,
            $this->titles,
            [
                'title' => $contract->exists ? $contract->name : 'Добавление договора',
            ]
        );
    }

    /**
     * Update excursion data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $patternIDs = $request['patternIDs'];
        $organization_id = $request['organization_id'];

        $current = Current::get($request);
        if (!isset($patternIDs) || empty($patternIDs)) {
            Contracts::tap(new ForOrganization($organization_id, true))->delete();
        } else {
            //deleteContracts
            Contracts::tap(new ForOrganization($organization_id, true))
                ->whereNotIn('pattern_id', $patternIDs)
                ->delete();

            $contracts = Contracts::tap(new ForOrganization($organization_id, true))
                ->whereIn('pattern_id', $patternIDs)
                ->get();
            $oldPatternIDs = $contracts->pluck('pattern_id')->toArray();
            $createPatternsIDs = array_diff($patternIDs, $oldPatternIDs);

            $patterns = Pattern::all();

            $items = [];
            foreach ($createPatternsIDs as $patternID) {
                $items[] = [
                    'name' => $patterns->where('id', $patternID)->first()->name,
                    'pattern_id' => $patternID,
                    'organization_id' => $current->organizationId()
                ];
            }

            Contracts::upsert($items, 'pattern_id', ['name', 'pattern_id', 'organization_id']);
        }

        return APIResponse::success('Договоры обновлены');
    }
}
