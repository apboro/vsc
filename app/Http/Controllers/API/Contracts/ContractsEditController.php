<?php

namespace App\Http\Controllers\API\Contracts;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\Contracts;
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
        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        /** @var Contracts|null $contract */
        if (!isset($request['id']) || $request['id'] === 0) {
            $contract = new Contracts();
        } else {
            $current = Current::get($request);
            $contract = Contracts::where('id', $request['id'])
                ->tap(new ForOrganization($current->organizationId(), true))
                ->first();
        }

        if ($contract === null) {
            return APIResponse::notFound('Организация не найдена');
        }

        $contract->name = $data['name'];
        $contract->organization_id = $data['organization_id'];
        $contract->pattern_id = $data['pattern_id'];
        $contract->save();

        return APIResponse::success(
            $contract->wasRecentlyCreated ? 'Договор добавлена' : 'Договор обновлен',
            ['id' => $contract->id]
        );
    }
}
