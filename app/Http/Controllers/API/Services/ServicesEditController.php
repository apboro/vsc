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
        'training_price' => 'nullable',
        'trainings_per_week' => 'required',
        'trainings_per_month' => 'required',
        'training_return_price' => 'required',
        'training_duration' => 'required',
        'group_limit' => 'required',
        'start_at' => 'required',
        'end_at' => 'required',
        'requisites_id' => 'required',
        'schedule_day_mon' => 'nullable',
        'schedule_day_tue' => 'nullable',
        'schedule_day_wed' => 'nullable',
        'schedule_day_thu' => 'nullable',
        'schedule_day_fri' => 'nullable',
        'schedule_day_sat' => 'nullable',
        'schedule_day_sun' => 'nullable',
        'schedule_time_mon' => 'nullable',
        'schedule_time_tue' => 'nullable',
        'schedule_time_wed' => 'nullable',
        'schedule_time_thu' => 'nullable',
        'schedule_time_fri' => 'nullable',
        'schedule_time_sat' => 'nullable',
        'schedule_time_sun' => 'nullable',
    ];

    protected array $titles = [
        'title' => 'Название',
        'status_id' => 'Статус услуги',
        'training_base_id' => 'Объект',
        'sport_kind_id' => 'Вид спорта',
        'monthly_price' => 'Стоимость в месяц, руб',
        'training_price' => 'Себестоимость за 1 занятие, руб',
        'trainings_per_week' => 'Количество занятий в неделю',
        'trainings_per_month' => 'Количество занятий в месяц',
        'training_return_price' => 'Стоимость за 1 занятие при перерасчете',
        'training_duration' => 'Длительность занятия, минут',
        'group_limit' => 'Максимальное количество мест в группе',
        'start_at' => 'Дата начала услуги',
        'end_at' => 'Дата окончания услуги',
        'requisites_id' => 'Реквизиты для договора',
        'schedule_day_mon' => 'Занятия в пн.',
        'schedule_day_tue' => 'Занятия в вт.',
        'schedule_day_wed' => 'Занятия в ср.',
        'schedule_day_thu' => 'Занятия в чт.',
        'schedule_day_fri' => 'Занятия в пт.',
        'schedule_day_sat' => 'Занятия в сб.',
        'schedule_day_sun' => 'Занятия в вс.',
        'schedule_time_mon' => 'Время начала занятий в пн.',
        'schedule_time_tue' => 'Время начала занятий в вт.',
        'schedule_time_wed' => 'Время начала занятий в ср.',
        'schedule_time_thu' => 'Время начала занятий в чт.',
        'schedule_time_fri' => 'Время начала занятий в пт.',
        'schedule_time_sat' => 'Время начала занятий в сб.',
        'schedule_time_sun' => 'Время начала занятий в вс.',
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

                'schedule_day_mon' => $service->schedule->mon,
                'schedule_day_tue' => $service->schedule->tue,
                'schedule_day_wed' => $service->schedule->wed,
                'schedule_day_thu' => $service->schedule->thu,
                'schedule_day_fri' => $service->schedule->fri,
                'schedule_day_sat' => $service->schedule->sat,
                'schedule_day_sun' => $service->schedule->sun,
                'schedule_time_mon' => $service->schedule->mon_start_time ? $service->schedule->mon_start_time->format('H:i') : null,
                'schedule_time_tue' => $service->schedule->tue_start_time ? $service->schedule->tue_start_time->format('H:i') : null,
                'schedule_time_wed' => $service->schedule->wed_start_time ? $service->schedule->wed_start_time->format('H:i') : null,
                'schedule_time_thu' => $service->schedule->thu_start_time ? $service->schedule->thu_start_time->format('H:i') : null,
                'schedule_time_fri' => $service->schedule->fri_start_time ? $service->schedule->fri_start_time->format('H:i') : null,
                'schedule_time_sat' => $service->schedule->sat_start_time ? $service->schedule->sat_start_time->format('H:i') : null,
                'schedule_time_sun' => $service->schedule->sun_start_time ? $service->schedule->sun_start_time->format('H:i') : null,
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

        $service->schedule->mon = $data['schedule_day_mon'];
        $service->schedule->tue = $data['schedule_day_tue'];
        $service->schedule->wed = $data['schedule_day_wed'];
        $service->schedule->thu = $data['schedule_day_thu'];
        $service->schedule->fri = $data['schedule_day_fri'];
        $service->schedule->sat = $data['schedule_day_sat'];
        $service->schedule->sun = $data['schedule_day_sun'];
        $service->schedule->mon_start_time = $data['schedule_time_mon'] ? Carbon::parse($data['schedule_time_mon']) : null;
        $service->schedule->tue_start_time = $data['schedule_time_tue'] ? Carbon::parse($data['schedule_time_tue']) : null;
        $service->schedule->wed_start_time = $data['schedule_time_wed'] ? Carbon::parse($data['schedule_time_wed']) : null;
        $service->schedule->thu_start_time = $data['schedule_time_thu'] ? Carbon::parse($data['schedule_time_thu']) : null;
        $service->schedule->fri_start_time = $data['schedule_time_fri'] ? Carbon::parse($data['schedule_time_fri']) : null;
        $service->schedule->sat_start_time = $data['schedule_time_sat'] ? Carbon::parse($data['schedule_time_sat']) : null;
        $service->schedule->sun_start_time = $data['schedule_time_sun'] ? Carbon::parse($data['schedule_time_sun']) : null;
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
