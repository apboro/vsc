<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Services\Service;
use Carbon\Carbon;
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
        'training_price' => 'required',
        'trainings_per_week' => 'required',
        'start_at' => 'required',
        'end_at' => 'required',
        'schedule' => 'required',
    ];

    protected array $titles = [
        'title' => 'Название',
        'status_id' => 'Статус услуги',
        'training_base_id' => 'Объект',
        'sport_kind_id' => 'Вид спорта',
        'monthly_price' => 'Стоимость в месяц, руб',
        'training_price' => 'Стоимость за одно занятие, руб',
        'trainings_per_week' => 'Количество занятий в неделю',
        'start_at' => 'Дата начала услуги',
        'end_at' => 'Дата окончания услуги',
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
                'training_price' => $service->training_price,
                'trainings_per_week' => $service->trainings_per_week,
                'start_at' => $service->start_at ? $service->start_at->format('Y-m-d') : null,
                'end_at' => $service->end_at ? $service->end_at->format('Y-m-d') : null,
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
        $service->training_price = $data['training_price'];
        $service->trainings_per_week = $data['trainings_per_week'];
        $service->start_at = Carbon::parse($data['start_at']);
        $service->end_at = Carbon::parse($data['end_at']);
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
