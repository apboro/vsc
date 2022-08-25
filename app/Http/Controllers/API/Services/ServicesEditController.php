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
        'trainings_per_month' => 'required',
        'training_return_price' => 'required',
        'training_duration' => 'required',
        'group_limit' => 'required',
        'start_at' => 'required',
        'end_at' => 'required',
        'requisites_id' => 'required',
        'schedule_days' => 'required',
        'schedule_start_time' => 'required',
    ];

    protected array $titles = [
        'title' => 'Название',
        'status_id' => 'Статус услуги',
        'training_base_id' => 'Объект',
        'sport_kind_id' => 'Вид спорта',
        'monthly_price' => 'Стоимость в месяц, руб',
        'training_price' => 'Стоимость за одно занятие, руб',
        'trainings_per_week' => 'Количество занятий в неделю',
        'trainings_per_month' => 'Количество занятий в месяц',
        'training_return_price' => 'Стоимость за 1 занятие при перерасчете',
        'training_duration' => 'Длительность занятия, минут',
        'group_limit' => 'Максимальное количество мест в группе',
        'start_at' => 'Дата начала услуги',
        'end_at' => 'Дата окончания услуги',
        'requisites_id' => 'Реквизиты для договора',
        'schedule_days' => 'Дни занятий',
        'schedule_start_time' => 'Время начала занятий',
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
                'trainings_per_month' => $service->trainings_per_month,
                'training_return_price' => $service->training_return_price,
                'training_duration' => $service->training_duration,
                'group_limit' => $service->group_limit,
                'start_at' => $service->start_at ? $service->start_at->format('Y-m-d') : null,
                'end_at' => $service->end_at ? $service->end_at->format('Y-m-d') : null,
                'requisites_id' => $service->requisites_id,
                'schedule_days' => array_values(
                    array_filter([
                        $service->schedule->mon ? 1 : null,
                        $service->schedule->tue ? 2 : null,
                        $service->schedule->wed ? 3 : null,
                        $service->schedule->thu ? 4 : null,
                        $service->schedule->fri ? 5 : null,
                        $service->schedule->sat ? 6 : null,
                        $service->schedule->sun ? 0 : null,
                    ], function ($day) {
                        return $day !== null;
                    })
                ),
                'schedule_start_time' => $service->schedule->start_time ? $service->schedule->start_time->format('H:i') : null,
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
        $service->trainings_per_month = $data['trainings_per_month'];
        $service->training_return_price = $data['training_return_price'];
        $service->training_duration = $data['training_duration'];
        $service->group_limit = $data['group_limit'];
        $service->start_at = Carbon::parse($data['start_at']);
        $service->end_at = Carbon::parse($data['end_at']);
        $service->requisites_id = $data['requisites_id'];
        if (!$service->exists) {
            $service->organization_id = $current->organizationId();
        }
        $service->save();

        $service->schedule->mon = in_array(1, $data['schedule_days'] ?? []);
        $service->schedule->tue = in_array(2, $data['schedule_days'] ?? []);
        $service->schedule->wed = in_array(3, $data['schedule_days'] ?? []);
        $service->schedule->thu = in_array(4, $data['schedule_days'] ?? []);
        $service->schedule->fri = in_array(5, $data['schedule_days'] ?? []);
        $service->schedule->sat = in_array(6, $data['schedule_days'] ?? []);
        $service->schedule->sun = in_array(0, $data['schedule_days'] ?? []);
        $service->schedule->start_time = $data['schedule_start_time'];
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
