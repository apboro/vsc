<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Service\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesEditController extends ApiEditController
{
    protected array $rules = [
        'title' => 'required',
        'status_id' => 'required',
        'training_base_id' => 'required',
        'sport_kind_id' => 'required',
        'monthly_price' => 'required',
        'schedule' => 'required',
    ];

    protected array $titles = [
        'title' => 'Название',
        'status_id' => 'Статус услуги',
        'training_base_id' => 'Объект',
        'sport_kind_id' => 'Вид спорта',
        'monthly_price' => 'Стоимость в месяц, руб',
        'schedule' => 'График занятий',
    ];

    /**
     * Get edit data for service.
     *
     * ID === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $current = Current::get($request);

        /** @var Service|null $service */
        $service = $this->firstOrNew(Service::class, $request, ['schedule'], [], ['organization_id' => $current->organizationId()]);

        if ($service === null) {
            return APIResponse::notFound('Услуга не найдена');
        }

        // send response
        return APIResponse::form(
            [
                'status_id' => $service->status_id,
                'title' => $service->title,
                'training_base_id' => $service->training_base_id,
                'sport_kind_id' => $service->sport_kind_id,
                'monthly_price' => $service->monthly_price,
                'schedule' => $service->schedule->text,
            ],
            $this->rules,
            $this->titles,
            [
                'title' => $service->exists ? $service->title : 'Добавление услуги',
            ]
        );
    }

    /**
     * Update service data.
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

        $current = Current::get($request);

        /** @var Service|null $service */
        $service = $this->firstOrNew(Service::class, $request, [], [], ['organization_id' => $current->organizationId()]);

        if ($service === null) {
            return APIResponse::notFound('Услуга не найдена');
        }

        $service->title = $data['title'];
        $service->setStatus($data['status_id'], false);
        $service->training_base_id = $data['training_base_id'];
        $service->sport_kind_id = $data['sport_kind_id'];
        $service->monthly_price = $data['monthly_price'];
        if (!$service->exists) {
            $service->organization_id = $current->organizationId();
        }
        $service->save();

        $service->schedule->text = $data['schedule'];
        $service->schedule->save();

        return APIResponse::success(
            $service->wasRecentlyCreated ? 'Услуга добавлена' : 'Данные услуги обновлены',
            [
                'id' => $service->id,
                'title' => $service->title,
            ]
        );
    }
}
