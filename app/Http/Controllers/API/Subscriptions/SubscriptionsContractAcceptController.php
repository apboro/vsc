<?php

namespace App\Http\Controllers\API\Subscriptions;

use App\Current;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Mail\SubscriptionContractMail;
use App\Models\Clients\Client;
use App\Models\Dictionaries\InvoiceStatus;
use App\Models\Dictionaries\InvoiceType;
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
            ->with([
                'contractData',
                'organizationData',
                'groupData',
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

        // send response
        return APIResponse::form(
            $this->getValues($contract),
            $this->getRules($contract),
            $this->getTitles(),
            [
                'is_group' => $contract->is_group,
                'is_legal' => $contract->is_legal,
            ]
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
                'organizationData',
                'groupData',
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

        if ($errors = $this->validate($data, $this->getRules($contract), $this->getTitles())) {
            return APIResponse::validationError($errors);
        }

        $isCreatingNew = $request->input('create', false);

        $contract->contractData->lastname = $data['lastname'];
        $contract->contractData->firstname = $data['firstname'];
        $contract->contractData->patronymic = $data['patronymic'];
        $contract->contractData->phone = $data['phone'];
        $contract->contractData->email = $data['email'];

        $contract->contractData->passport_serial = $data['passport_serial'] ?? null;
        $contract->contractData->passport_number = $data['passport_number'] ?? null;
        $contract->contractData->passport_place = $data['passport_place'] ?? null;
        $contract->contractData->passport_date = $data['passport_date'] ?? false ? Carbon::parse($data['passport_date']) : null;
        $contract->contractData->passport_code = $data['passport_code'] ?? null;
        $contract->contractData->registration_address = $data['registration_address'] ?? null;

        $contract->contractData->ward_lastname = $data['ward_lastname'] ?? null;
        $contract->contractData->ward_firstname = $data['ward_firstname'] ?? null;
        $contract->contractData->ward_patronymic = $data['ward_patronymic'] ?? null;
        $contract->contractData->ward_birth_date = $data['ward_birth_date'] ?? false ? Carbon::parse($data['ward_birth_date']) : null;
        $contract->contractData->ward_document = $data['ward_document'] ?? null;
        $contract->contractData->ward_document_date = $data['ward_document_date'] ?? false ? Carbon::parse($data['ward_document_date']) : null;

        $contract->organizationData->organization_name = $data['organization_name'] ?? null;
        $contract->organizationData->in_face = $data['in_face'] ?? null;
        $contract->organizationData->inn = $data['inn'] ?? null;
        $contract->organizationData->kpp = $data['kpp'] ?? null;
        $contract->organizationData->checking_account = $data['checking_account'] ?? null;
        $contract->organizationData->bic = $data['bic'] ?? null;
        $contract->organizationData->corr_account = $data['corr_account'] ?? null;
        $contract->organizationData->org_email = $data['org_email'] ?? null;
        $contract->organizationData->org_phone = $data['org_phone'] ?? null;

        $contract->groupData->girls_1_count = $data['girls_1_count'] ?? null;
        $contract->groupData->boys_1_count = $data['boys_1_count'] ?? null;
        $contract->groupData->girls_2_count = $data['girls_2_count'] ?? null;
        $contract->groupData->boys_2_count = $data['boys_2_count'] ?? null;
        $contract->groupData->girls_3_count = $data['girls_3_count'] ?? null;
        $contract->groupData->boys_3_count = $data['boys_3_count'] ?? null;
        $contract->groupData->ward_count = $data['ward_count'] ?? null;
        $contract->groupData->trainer_count = $data['trainer_count'] ?? null;
        $contract->groupData->attendant_count = $data['attendant_count'] ?? null;

        $contract->contractData->additional_conditions = $data['additional_conditions'] ?? null;

        $contract->contractData->total_price = $data['total_price'] ?? null;
        $contract->contractData->additional_price = $data['additional_price'] ?? null;
        $contract->contractData->group_price = $data['group_price'] ?? null;

        if ($isCreatingNew) {
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
            $contract->contractData->sport_kind = implode(', ', $contract->subscription->service->sportKinds->pluck('name')->toArray());
            $contract->contractData->training_base_address = $contract->subscription->service->trainingBase->info->address;
            $contract->contractData->monthly_price = $contract->subscription->service->monthly_price;
            $contract->contractData->training_return_price = $contract->subscription->service->training_return_price;
        }

        $contract->contractData->save();

        $contract->discount_id = $data['discount_id'] ?? null;

        if ($isCreatingNew) {
            $contract->setStatus(SubscriptionContractStatus::accepted, false);
            $contract->start_at = Carbon::now()->startOfDay();
            $contract->end_at = $contract->subscription->service->end_at;
        }

        $contract->save();

        if ($isCreatingNew) {
            // Assign contract number
            DB::transaction(function () use ($contract, $current) {
                $contract->number = SubscriptionContract::getNewNumber($current->organizationId());
                $contract->save();

                //  Create a recalculation type invoice from current date to the end of month
                $moderationRequired = $contract->subscription->client->account->amount > 0;
                $lastDayOfThisMonth = new Carbon('last day of this month');

                $contract->invoices()->create([
                    'date_from' => $contract->start_at,
                    'date_to' => $lastDayOfThisMonth,
                    'moderation_required' => $moderationRequired,
                    'status_id' => $moderationRequired ? InvoiceStatus::draft : InvoiceStatus::ready,
                    'type_id' => InvoiceType::recalculation,
                    'amount_to_pay' => $contract->calculateRecalculationInvoiceTotal($contract->start_at, $lastDayOfThisMonth),
                    'comment' => 'Автоматический созданный счет по контракту ' . $contract->id,
                ]);
            });
            // send a link to client
            try {
                Mail::send(new SubscriptionContractMail($contract));
            } catch (Exception $exception) {
                Log::channel('outgoing_mail_errors')->error($exception->getMessage());
                throw $exception;
            }
            $contract->subscription->setStatus(SubscriptionStatus::sent);
        }

        return APIResponse::success($isCreatingNew ? 'Договор на оказание услуг сформирован и отправлен клиенту' : 'Данные договора обновлены');
    }

    /**
     * Update clients data automatically from clients card
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateClientData(Request $request): JsonResponse
    {
        $clientId = $request->input('client_id');

        if ($clientId === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        /** @var Client|null $client */
        $client = Client::query()->find($clientId);

        if ($client === null) {
            return APIResponse::notFound('Клиент не найден');
        }

        $client->updateContractsData([SubscriptionStatus::sent]);

        return APIResponse::success('Данные обновлены');
    }

    protected function getValues(SubscriptionContract $contract): array
    {
        $values = [
            'lastname' => $contract->contractData->lastname,
            'firstname' => $contract->contractData->firstname,
            'patronymic' => $contract->contractData->patronymic,
            'phone' => $contract->contractData->phone,
            'email' => $contract->contractData->email,

            'discount_id' => $contract->discount_id,
        ];

        $clientData = [
            'passport_serial' => $contract->contractData->passport_serial,
            'passport_number' => $contract->contractData->passport_number,
            'passport_place' => $contract->contractData->passport_place,
            'passport_date' => $contract->contractData->passport_date ? $contract->contractData->passport_date->format('Y-m-d') : null,
            'passport_code' => $contract->contractData->passport_code,
            'registration_address' => $contract->contractData->registration_address,
        ];

        if ($contract->is_group ?? false) {
            if ($contract->is_legal) {
                $values = array_merge($values, [
                    'organization_name' => $contract->organizationData->organization_name,
                    'in_face' => $contract->organizationData->in_face,
                    'inn' => $contract->organizationData->inn,
                    'kpp' => $contract->organizationData->kpp,
                    'checking_account' => $contract->organizationData->checking_account,
                    'bic' => $contract->organizationData->bic,
                    'corr_account' => $contract->organizationData->corr_account,
                    'org_email' => $contract->organizationData->org_email,
                    'org_phone' => $contract->organizationData->org_phone,
                ]);
            } else {
                $values = array_merge($values, $clientData);
            }
            $values = array_merge($values, [
                'girls_1_count' => $contract->groupData->girls_1_count,
                'boys_1_count' => $contract->groupData->boys_1_count,
                'girls_2_count' => $contract->groupData->girls_2_count,
                'boys_2_count' => $contract->groupData->boys_2_count,
                'girls_3_count' => $contract->groupData->girls_3_count,
                'boys_3_count' => $contract->groupData->boys_3_count,
                'ward_count' => $contract->groupData->ward_count,
                'trainer_count' => $contract->groupData->trainer_count,
                'attendant_count' => $contract->groupData->attendant_count,

                'additional_conditions' => $contract->contractData->additional_conditions,

                'per_ward_price' => $contract->subscription->service->price ?? 0,
                'days_count' => $contract->contractData->days_count ?? 14,
                'group_price' => $contract->contractData->group_price ?:
                    ($contract->groupData->ward_count * $contract->subscription->service->price ?? 0),
                'additional_price' => $contract->contractData->additional_price ?:
                    0,
                'total_price' => $contract->contractData->total_price ?:
                    ($contract->groupData->ward_count * $contract->subscription->service->price ?? 0),
            ]);
        } else {
            $values = array_merge($values, $clientData);
            $values = array_merge($values, [
                'ward_lastname' => $contract->contractData->ward_lastname,
                'ward_firstname' => $contract->contractData->ward_firstname,
                'ward_patronymic' => $contract->contractData->ward_patronymic,
                'ward_birth_date' => $contract->contractData->ward_birth_date ?
                    $contract->contractData->ward_birth_date->format('Y-m-d') : null,
                'ward_document' => $contract->contractData->ward_document,
                'ward_document_date' => $contract->contractData->ward_document_date ?
                    $contract->contractData->ward_document_date->format('Y-m-d') : null,
            ]);
        }

        return $values;
    }

    private function getRules(?SubscriptionContract $contract = null): array
    {
        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'patronymic' => 'required',
            'phone' => 'required',
            'email' => 'required|email|bail',
        ];

        $clientRules = [
            'passport_serial' => 'required',
            'passport_number' => 'required',
            'passport_place' => 'required',
            'passport_date' => 'required',
            'passport_code' => 'required',
            'registration_address' => 'required',
        ];

        if ($contract->is_group ?? false) {
            if ($contract->is_legal) {
                $rules = array_merge($rules, [
                     'organization_name' => 'required',
                     'in_face' => 'required',
                     'inn' => 'required',
                     'kpp' => 'required',
                     'checking_account' => 'required',
                     'bic' => 'required',
                     'corr_account' => 'required',
                     'org_email' => 'required',
                     'org_phone' => 'required',
                ]);
            } else {
                $rules = array_merge($rules, $clientRules);
            }
            $rules = array_merge($rules, [
                'girls_1_count' => 'nullable',
                'boys_1_count' => 'nullable',
                'girls_2_count' => 'nullable',
                'boys_2_count' => 'nullable',
                'girls_3_count' => 'nullable',
                'boys_3_count' => 'nullable',
                'ward_count' => 'required',
                'trainer_count' => 'required',
                'attendant_count' => 'required',

                'additional_conditions' => 'nullable',

                'days_count' => 'required',
                'group_price' => 'required',
                'additional_price' => 'nullable',
                'total_price' => 'required',
            ]);
        } else {
            $rules = array_merge($rules, $clientRules);
            $rules = array_merge($rules, [
                'ward_lastname' => 'required',
                'ward_firstname' => 'required',
                'ward_patronymic' => 'required',
                'ward_birth_date' => 'required',
                'ward_document' => 'required',
                'ward_document_date' => 'required',
                'discount_id' => 'nullable',
            ]);
        }

        return $rules;
    }

    private function getTitles(): array
    {
        return [
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

            'girls_1_count' => 'Девочек до 10 лет',
            'boys_1_count' => 'Мальчиков до 10 лет',
            'girls_2_count' => 'Девочек 10-17 лет',
            'boys_2_count' => 'Мальчиков 10-17 лет',
            'girls_3_count' => 'Девочек 18 лет и старше',
            'boys_3_count' => 'Мальчиков 18 лет и старше',
            'ward_count' => 'Общее количество детей',
            'trainer_count' => 'Количество тренеров',
            'attendant_count' => 'Количество сопровождающих',

            'organization_name' => 'Название организации',
            'in_face' => 'В лице',
            'inn' => 'ИНН',
            'kpp' => 'КПП/ОГРН',
            'checking_account' => 'Расчетный счет',
            'bic' => 'БИК',
            'corr_account' => 'К/с',
            'org_email' => 'Email',
            'org_phone' => 'Телефон',

            'days_count' => 'Количество дней',
            'group_price' => 'Стоимость услуги за группу, руб.',
            'additional_price' => 'Добавлено к стоимости, руб.',
            'total_price' => 'Итоговая стоимость, руб.',

            'discount_id' => 'Льгота',
        ];
    }
}
