<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Mail\SubscriptionContractMail;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SubscriptionsContractAcceptController extends ApiEditController
{
    protected array $rules = [
        'lastname' => 'required',
        'firstname' => 'required',
        'patronymic' => 'required',
        'phone' => 'required',
        'email' => 'required|email|bail',
        'passport_serial' => 'required',
        'passport_number' => 'required',
        'passport_place' => 'required',
        'passport_date' => 'required',
        'passport_code' => 'required',
        'registration_address' => 'required',
        'ward_lastname' => 'required',
        'ward_firstname' => 'required',
        'ward_patronymic' => 'required',
        'ward_birth_date' => 'required',
        'ward_document' => 'required',
        'ward_document_date' => 'required',
        'discount_id' => 'nullable',
    ];

    protected array $titles = [
        'lastname' => 'Фамилия',
        'firstname' => 'Имя',
        'patronymic' => 'Отчество',
        'phone' => 'Телефон',
        'email' => 'Email',
        'passport_serial' => 'Серия паспорта',
        'passport_number' => 'Номер паспорта',
        'passport_place' => 'Кем выдан',
        'passport_date' => 'Дата выдачи',
        'passport_code' => 'Код подразделения',
        'registration_address' => 'Адрес регистрации',
        'ward_lastname' => 'Фамилия',
        'ward_firstname' => 'Имя',
        'ward_patronymic' => 'Отчество',
        'ward_birth_date' => 'Дата рождения',
        'ward_document' => 'Свидетельство о рождении',
        'ward_document_date' => 'Дата выдачи',
        'discount_id' => 'Льгота',
    ];

    /**
     * Get edit data for training base.
     *
     * id === 0 is for new
     *
     * @param Request $request
     *
     * @return  JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        $current = Current::get($request);

        $id = $request->input('id');
        $subscriptionId = $request->input('subscription_id');

        /** @var SubscriptionContract|null $contract */
        $contract = SubscriptionContract::query()
            ->with(['contractData'])
            ->where('id', $id)
            ->whereHas('subscription', function (Builder $query) use ($current, $subscriptionId) {
                $query
                    ->where('id', $subscriptionId)
                    ->tap(new ForOrganization($current->organizationId()));
            })
            ->first();

        if ($contract === null) {
            return APIResponse::notFound('Документ не найден');
        }

        // send response
        return APIResponse::form(
            [
                'lastname' => $contract->contractData->lastname,
                'firstname' => $contract->contractData->firstname,
                'patronymic' => $contract->contractData->patronymic,
                'phone' => $contract->contractData->phone,
                'email' => $contract->contractData->email,
                'passport_serial' => $contract->contractData->passport_serial,
                'passport_number' => $contract->contractData->passport_number,
                'passport_place' => $contract->contractData->passport_place,
                'passport_date' => $contract->contractData->passport_date->format('Y-m-d'),
                'passport_code' => $contract->contractData->passport_code,
                'registration_address' => $contract->contractData->registration_address,
                'ward_lastname' => $contract->contractData->ward_lastname,
                'ward_firstname' => $contract->contractData->ward_firstname,
                'ward_patronymic' => $contract->contractData->ward_patronymic,
                'ward_birth_date' => $contract->contractData->ward_birth_date->format('Y-m-d'),
                'ward_document' => $contract->contractData->ward_document,
                'ward_document_date' => $contract->contractData->ward_document_date->format('Y-m-d'),
                'discount_id' => $contract->discount_id,
            ],
            $this->rules,
            $this->titles,
        );
    }

    /**
     * Update training base data.
     *
     * @param Request $request
     *
     * @return  JsonResponse
     * @throws Exception
     */
    public function update(Request $request): JsonResponse
    {
        $current = Current::get($request);

        $id = $request->input('id');
        $subscriptionId = $request->input('subscription_id');

        /** @var SubscriptionContract|null $contract */
        $contract = SubscriptionContract::query()
            ->with([
                'contractData',
                'subscription.service.requisites',
                'subscription.service.sportKind',
                'subscription.service.trainingBase.info',
            ])
            ->where('id', $id)
            ->whereHas('subscription', function (Builder $query) use ($current, $subscriptionId) {
                $query
                    ->where('id', $subscriptionId)
                    ->tap(new ForOrganization($current->organizationId()));
            })
            ->first();

        if ($contract === null) {
            return APIResponse::notFound('Документ не найден');
        }

        $data = $this->getData($request);

        if ($errors = $this->validate($data, $this->rules, $this->titles)) {
            return APIResponse::validationError($errors);
        }

        $contract->contractData->lastname = $data['lastname'];
        $contract->contractData->firstname = $data['firstname'];
        $contract->contractData->patronymic = $data['patronymic'];
        $contract->contractData->phone = $data['phone'];
        $contract->contractData->email = $data['email'];
        $contract->contractData->passport_serial = $data['passport_serial'];
        $contract->contractData->passport_number = $data['passport_number'];
        $contract->contractData->passport_place = $data['passport_place'];
        $contract->contractData->passport_date = Carbon::parse($data['passport_date']);
        $contract->contractData->passport_code = $data['passport_code'];
        $contract->contractData->registration_address = $data['registration_address'];
        $contract->contractData->ward_lastname = $data['ward_lastname'];
        $contract->contractData->ward_firstname = $data['ward_firstname'];
        $contract->contractData->ward_patronymic = $data['ward_patronymic'];
        $contract->contractData->ward_birth_date = Carbon::parse($data['ward_birth_date']);
        $contract->contractData->ward_document = $data['ward_document'];
        $contract->contractData->ward_document_date = Carbon::parse($data['ward_document_date']);

        $contract->contractData->organization_title = $contract->subscription->service->requisites->organization_title;
        $contract->contractData->organization_inn = $contract->subscription->service->requisites->organization_inn;
        $contract->contractData->organization_kpp = $contract->subscription->service->requisites->organization_kpp;
        $contract->contractData->bank_account = $contract->subscription->service->requisites->bank_account;
        $contract->contractData->bank_title = $contract->subscription->service->requisites->bank_title;
        $contract->contractData->bank_bik = $contract->subscription->service->requisites->bank_bik;
        $contract->contractData->bank_ks = $contract->subscription->service->requisites->bank_ks;

        $contract->contractData->service_start_date = $contract->subscription->service->start_at;
        $contract->contractData->service_end_date = $contract->subscription->service->end_at;

        $contract->contractData->trainings_per_week = $contract->subscription->service->trainings_per_week;
        $contract->contractData->trainings_per_month = $contract->subscription->service->trainings_per_month;
        $contract->contractData->training_duration = $contract->subscription->service->training_duration;

        $contract->contractData->sport_kind = $contract->subscription->service->sportKind->name;
        $contract->contractData->training_base_address = $contract->subscription->service->trainingBase->info->address;

        $contract->contractData->monthly_price = $contract->subscription->service->monthly_price;
        $contract->contractData->training_return_price = $contract->subscription->service->training_return_price;

        $contract->contractData->save();

        $contract->setStatus(SubscriptionContractStatus::accepted, false);
        $contract->discount_id = $data['discount_id'];
        $contract->start_at = Carbon::now()->startOfDay();
        $contract->end_at = $contract->subscription->service->end_at;
        $contract->save();

        // Assign contract number
        DB::transaction(function () use ($contract, $current) {
            $contract->number = SubscriptionContract::getNewNumber($current->organizationId());
            $contract->save();
        });

        // send a link to client
        try {
            Mail::send(new SubscriptionContractMail($contract));
        } catch (Exception $exception) {
            Log::channel('outgoing_mail_errors')->error($exception->getMessage());
            throw $exception;
        }

        $contract->subscription->setStatus(SubscriptionStatus::sent);

        return APIResponse::success('договор на оказание услуг сформирован и отправлен клиенту');
    }
}
