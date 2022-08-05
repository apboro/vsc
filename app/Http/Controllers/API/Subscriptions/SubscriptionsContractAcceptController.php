<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Subscriptions\SubscriptionContract;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
     */
    public function update(Request $request): JsonResponse
    {
        $current = Current::get($request);

        $id = $request->input('id');
        $subscriptionId = $request->input('subscription_id');

        /** @var SubscriptionContract|null $contract */
        $contract = SubscriptionContract::query()
            ->with(['contractData', 'subscription.service'])
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

        $contract->contractData->save();

        $contract->setStatus(SubscriptionContractStatus::accepted, false);
        $contract->start_at = Carbon::now()->startOfDay();
        $contract->end_at = $contract->subscription->service->end_at;
        $contract->save();

        // TODO send email

        return APIResponse::success('договор на оказание услуг сформирован и отправлен клиенту');
    }
}
