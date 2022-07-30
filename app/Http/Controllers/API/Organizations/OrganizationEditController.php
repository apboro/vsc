<?php

namespace App\Http\Controllers\API\Organizations;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Organization\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationEditController extends ApiEditController
{
    protected array $rules = [
        'title' => 'required',
        'status_id' => 'required',
    ];

    protected array $titles = [
        'title' => 'Название организации',
        'status_id' => 'Статус организации',
    ];

    /**
     * Get edit data for the organization.
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        /** @var Organization|null $organization */
        $organization = $this->firstOrNew(Organization::class, $request, ['info']);

        if ($organization === null) {
            return APIResponse::notFound('Организация не найдена');
        }

        // send response
        return APIResponse::form(
            [
                'title' => $organization->title,
                'status_id' => $organization->status_id,
            ],
            $this->rules,
            $this->titles,
            [
                'title' => $organization->exists ? $organization->title : 'Добавление организации',
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

        /** @var Organization|null $organization */
        $organization = $this->firstOrNew(Organization::class, $request);

        if ($organization === null) {
            return APIResponse::notFound('Организация не найдена');
        }

        $organization->title = $data['title'];
        $organization->setStatus($data['status_id'], false);
        $organization->save();

        return APIResponse::success(
            $organization->wasRecentlyCreated ? 'Организация добавлена' : 'Данные организации обновлены',
            ['id' => $organization->id]
        );
    }
}
