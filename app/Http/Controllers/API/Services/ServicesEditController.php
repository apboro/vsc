<?php

namespace App\Http\Controllers\API\Services;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\ServiceTypes;
use App\Models\ServicePhone;
use App\Models\Services\Service;
use App\Models\Services\ServiceProgram;
use App\Models\UserService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesEditController extends ApiEditController
{
    protected array $rules = [
        'title' => 'required',
        'status_id' => 'required',
        'type_program_id' => 'required',
        'training_base_id' => 'required',
        'contract_id' => 'required',
        'letter_id' => 'required',
        'sport_kind_id' => 'required',
        'sport_kinds' => 'required',
        'monthly_price' => 'nullable',
        'training_price' => 'nullable',
        'trainings_per_week' => 'nullable',
        'trainings_per_month' => 'nullable',
        'training_return_price' => 'nullable',
        'training_duration' => 'nullable',
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
        'description' => 'nullable',
        'price' => 'nullable',
        'date_deposit_funds' => 'nullable',
        'advance_payment' => 'nullable',
        'date_advance_payment' => 'nullable',
        'refund_amount' => 'nullable',
        'daily_price' => 'nullable',
        'price_deduction_advance' => 'nullable',
        'email'=>'nullable|email',
        'phones'=>'nullable'
    ];

    protected array $titles = [
        'title' => 'Название',
        'status_id' => 'Статус услуги',
        'training_base_id' => 'Объект',
        'sport_kind_id' => 'Вид спорта',
        'sport_kinds' => 'Виды спорта',
        'type_program_id' => 'Тип программы',
        'price' => 'Стоимость услуги руб.',
        'description' => 'Описание услуги',
        'monthly_price' => 'Стоимость в месяц, руб',
        'training_price' => 'Себестоимость за 1 занятие, руб',
        'trainings_per_week' => 'Количество занятий в неделю',
        'trainings_per_month' => 'Количество занятий в месяц',
        'training_return_price' => 'Стоимость за 1 занятие при перерасчете',
        'training_duration' => 'Длительность занятия, минут',
        'group_limit' => 'Максимальное количество мест в группе',
        'start_at' => 'Дата начала услуги',
        'end_at' => 'Дата окончания услуги',
        'date_deposit_funds' => 'Дата внесения средств',
        'advance_payment' => 'Авансовый платеж',
        'date_advance_payment' => 'Дата внесения аванса',
        'refund_amount' => 'Сумма возврата при расторжении договора по инициативе клиента, в день',
        'daily_price' => 'Стоимость за 1 день, руб',
        'price_deduction_advance' => 'Стоимость услуги за вычетом аванса, руб',
        'requisites_id' => 'Реквизиты для договора',
        'contract_id' => 'Шаблон договора',
        'letter_id' => 'Шаблон письма',
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
        'email' => 'Email',
        'phones'=>'Телефон',
        'responsible_user_ids'=>'Ответственный менеджер'
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
                'type_program_id' => $service->type_program_id,
                'contract_id' => $service->contract_id,
                'letter_id' => $service->letter_id,
                'title' => $service->title,
                'training_base_id' => $service->training_base_id,
                'sport_kind_id' => $service->sport_kind_id,
                'sport_kinds' => $service->sportKinds()->pluck('sport_kind_id')->toArray(),
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
                'description' => $service->description,
                'price' => $service->price,
                'date_deposit_funds' => $service->date_deposit_funds,
                'advance_payment' => $service->advance_payment,
                'date_advance_payment' => $service->date_advance_payment,
                'refund_amount' => $service->refund_amount,
                'daily_price' => $service->daily_price,
                'price_deduction_advance' => $service->price_deduction_advance,

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

        $regulars = ServiceProgram::select('id')
            ->where('service_type_id', ServiceTypes::regular)
            ->where('organization_id', $current->organizationId())
            ->get()
            ->pluck('id')
            ->toArray();

        $singleType = ServiceProgram::select('id')
            ->where('service_type_id', ServiceTypes::one_time)
            ->where('organization_id', $current->organizationId())
            ->get()
            ->pluck('id')
            ->toArray();

        $rules = $this->rules;

        if (in_array($data['type_program_id'], $regulars)) {
            $rules['monthly_price'] = 'required';
            $rules['training_price'] = 'required';
            $rules['training_return_price'] = 'required';
            $rules['training_duration'] = 'required';
            $rules['trainings_per_month'] = 'required';
            $rules['trainings_per_week'] = 'required';

            $rules['price'] = 'nullable';
            $rules['date_deposit_funds'] = 'nullable';
            $rules['advance_payment'] = 'nullable';
            $rules['date_advance_payment'] = 'nullable';
            $rules['refund_amount'] = 'nullable';
            $rules['daily_price'] = 'nullable';
            $rules['price_deduction_advance'] = 'nullable';
        }

        if (in_array($data['type_program_id'], $singleType)) {
            $rules['price'] = 'required';
            $rules['date_deposit_funds'] = 'required';
            $rules['advance_payment'] = 'required';
            $rules['date_advance_payment'] = 'required';
            $rules['refund_amount'] = 'required';
            $rules['daily_price'] = 'required';
            $rules['price_deduction_advance'] = 'required';

            $rules['monthly_price'] = 'nullable';
            $rules['training_price'] = 'nullable';
            $rules['training_return_price'] = 'nullable';
            $rules['training_duration'] = 'nullable';
            $rules['trainings_per_month'] = 'nullable';
            $rules['trainings_per_week'] = 'nullable';
        }

        if ($errors = $this->validate($data, $rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        if ($service === null) {
            return APIResponse::notFound('Услуга не найдена');
        }

        $service->title = $data['title'];
        $service->setStatus($data['status_id'], false);
        $service->training_base_id = $data['training_base_id'];
        $service->type_program_id = $data['type_program_id'];
        $service->contract_id = $data['contract_id'];
        $service->letter_id = $data['letter_id'];
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
        $service->description = $data['description'];
        $service->date_deposit_funds = $data['date_deposit_funds'];
        $service->advance_payment = $data['advance_payment'];
        $service->date_advance_payment = $data['date_advance_payment'];
        $service->refund_amount = $data['refund_amount'];
        $service->daily_price = $data['daily_price'];
        $service->price_deduction_advance = $data['price_deduction_advance'];
        $service->price = $data['price'];
        $service->email = !empty($data['email']) ?  $data['email'] : null;
        if (!$service->exists) {
            $service->organization_id = $current->organizationId();
        }
        $service->save();

        if(!empty($data['phones'])){
            ServicePhone::where('service_id',$service->id)->delete();
            foreach($data['phones'] as $row){
                ServicePhone::create([
                    'service_id'=>$service->id,
                    'phone'=>$row
                ]);
            }
        }

        if(!empty($data['responsible_user_ids'])){
            UserService::where('service_id',$service->id)->delete();
            foreach($data['responsible_user_ids'] as $row){
                UserService::create([
                    'service_id'=>$service->id,
                    'user_id'=>$row
                ]);
            }
        }


        $service->sportKinds()->sync($data['sport_kinds']);
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
