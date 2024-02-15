<?php

namespace App\Http\Controllers\LeadsGroup;

use App\Helpers\SubscriptionContractPdf;
use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Models\Subscriptions\SubscriptionContractData;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadGroupContractController extends ApiEditController
{
    /**
     * Save subscription contract data.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function contract(Request $request): JsonResponse
    {
        $organizationId = LeadSession::getOrganizationId($request);

        if ($organizationId === null) {
            return APIResponse::error('Ошибка сессии.', ['oid is null']);
        }

        $subscriptionId = $request->input('subscription_id');

        $subscription = Subscription::query()
            ->with([
                'service',
                'client.user.profile',
                'lead.groupData',
            ])
            ->tap(new ForOrganization($organizationId))
            ->where('id', $subscriptionId)
            ->first();

        if ($subscription === null) {
            return APIResponse::error('Ошибка параметров.');
        }
        if (!$subscription->hasStatus(SubscriptionStatus::new) && !$subscription->hasStatus(SubscriptionStatus::fill)) {
            return APIResponse::error('Форма уже заполнена.');
        }

        /** @var Subscription $subscription */

        $isContractLegal = $subscription->lead->groupData->is_contract_legal;

        $data = $this->getData($request);

        // validate
        $rules = [
            'lastname' => 'required',
            'firstname' => 'required',
            'patronymic' => 'required',
            'phone' => 'required',
            'email' => 'required|email|bail',
            'additional_conditions' => 'nullable|string',

            'girls_1_count' => 'nullable|integer|gte:0',
            'boys_1_count' => 'nullable|integer|gte:0',
            'girls_2_count' => 'nullable|integer|gte:0',
            'boys_2_count' => 'nullable|integer|gte:0',
            'girls_3_count' => 'nullable|integer|gte:0',
            'boys_3_count' => 'nullable|integer|gte:0',
            'ward_count' => 'required|integer|gte:0',
            'trainer_count' => 'required|integer|gte:0',
            'attendant_count' => 'required|integer|gte:0',
        ];
        if ($isContractLegal) {
            $rules['organization_name'] = 'required';
            $rules['in_face'] = 'required';
            $rules['inn'] = 'required';
            $rules['kpp'] = 'required';
            $rules['checking_account'] = 'required';
            $rules['bic'] = 'required';
            $rules['corr_account'] = 'required';
            $rules['org_email'] = 'required';
            $rules['org_phone'] = 'required';
        } else {
            $rules['birth_date'] = 'required';
            $rules['passport_serial'] = 'required';
            $rules['passport_number'] = 'required';
            $rules['passport_place'] = 'required';
            $rules['passport_date'] = 'required';
            $rules['passport_code'] = 'required';
            $rules['registration_address'] = 'required';
        }

        $titles = [
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'patronymic' => 'Отчество',
            'phone' => 'Телефон',
            'email' => 'Email',

            'girls_1_count' => 'Девочек до 10 лет',
            'boys_1_count' => 'Мальчиков до 10 лет',
            'girls_2_count' => 'Девочек 10-17 лет',
            'boys_2_count' => 'Мальчиков 10-17 лет',
            'girls_3_count' => 'Девочек 18 лет и старше',
            'boys_3_count' => 'Мальчиков 18 лет и старше',
            'ward_count' => 'Общее количество детей',
            'trainer_count' => 'Количество тренеров',
            'attendant_count' => 'Количество сопровождающих',

            'birth_date' => 'Дата рождения',
            'passport_serial' => 'Серия паспорта',
            'passport_number' => 'Номер паспорта',
            'passport_place' => 'Кем выдан',
            'passport_date' => 'Дата выдачи',
            'passport_code' => 'Код подразделения',
            'registration_address' => 'Адрес регистрации',

            'organization_name' => 'Название организации',
            'in_face' => 'В лице',
            'inn' => 'ИНН',
            'kpp' => 'КПП/ОГРН',
            'checking_account' => 'Расчетный счет',
            'bic' => 'БИК',
            'corr_account' => 'К/с',
            'org_email' => 'Email',
            'org_phone' => 'Телефон',
        ];

        if ($errors = $this->validate($data, $rules, $titles)) {
            return APIResponse::validationError($errors);
        }

        if (
            (int)$data['girls_1_count'] + (int)$data['boys_1_count'] +
            (int)$data['girls_2_count'] + (int)$data['boys_2_count'] +
            (int)$data['girls_3_count'] + (int)$data['boys_3_count'] !==
            (int)$data['ward_count']
        ) {
            return APIResponse::error('Общее количество детей не совпадает');
        }

        try {
            DB::transaction(static function () use ($subscription, $data, $isContractLegal) {
                // add contract
                $contract = new SubscriptionContract();
                $contract->subscription_id = $subscription->id;
                $contract->setStatus(SubscriptionContractStatus::draft, false);
                $contract->save();

                // fill contract data
                $contract->contractData->lastname = trim($data['lastname']);
                $contract->contractData->firstname = trim($data['firstname']);
                $contract->contractData->patronymic = trim($data['patronymic']);
                $contract->contractData->phone = $data['phone'];
                $contract->contractData->email = trim($data['email']);
                if ($isContractLegal) {
                    // organization data if contract is legal
                    $contract->organizationData->organization_name = trim($data['organization_name']);
                    $contract->organizationData->in_face = trim($data['in_face']);
                    $contract->organizationData->inn = trim($data['inn']);
                    $contract->organizationData->kpp = trim($data['kpp']);
                    $contract->organizationData->checking_account = trim($data['checking_account']);
                    $contract->organizationData->bic = trim($data['bic']);
                    $contract->organizationData->corr_account = trim($data['corr_account']);
                    $contract->organizationData->org_email = trim($data['org_email']);
                    $contract->organizationData->org_phone = $data['org_phone'];
                    $contract->organizationData->save();
                } else {
                    // client data if contract is individual
                    $contract->contractData->birth_date = Carbon::parse($data['birth_date']);
                    $contract->contractData->passport_serial = trim($data['passport_serial']);
                    $contract->contractData->passport_number = trim($data['passport_number']);
                    $contract->contractData->passport_place = trim($data['passport_place']);
                    $contract->contractData->passport_date = Carbon::parse($data['passport_date']);
                    $contract->contractData->passport_code = trim($data['passport_code']);
                    $contract->contractData->registration_address = trim($data['registration_address']);
                }
                // Group data
                $contract->groupData->girls_1_count = trim($data['girls_1_count']);
                $contract->groupData->boys_1_count = trim($data['boys_1_count']);
                $contract->groupData->girls_2_count = trim($data['girls_2_count']);
                $contract->groupData->boys_2_count = trim($data['boys_2_count']);
                $contract->groupData->girls_3_count = trim($data['girls_3_count']);
                $contract->groupData->boys_3_count = trim($data['boys_3_count']);
                $contract->groupData->ward_count = trim($data['ward_count']);
                $contract->groupData->trainer_count = trim($data['trainer_count']);
                $contract->groupData->attendant_count = trim($data['attendant_count']);
                $contract->groupData->save();

                $contract->contractData->additional_conditions = trim($data['additional_conditions']);

                $contract->contractData->sport_kind = implode(', ', $subscription->service->sportKinds->pluck('name')->toArray());
                $contract->contractData->service_name = $subscription->service->title;
                $contract->contractData->training_base_name = $subscription->service->trainingBase->title;
                $contract->contractData->advance_payment = $subscription->service->advance_payment;
                $contract->contractData->date_advance_payment = $subscription->service->date_advance_payment;
                $contract->contractData->date_deposit_funds = $subscription->service->date_deposit_funds;
                $contract->contractData->price = $subscription->service->price;
                $contract->contractData->daily_price = $subscription->service->daily_price;
                $contract->contractData->refund_amount = $subscription->service->refund_amount;
                $contract->contractData->save();

                $client = $subscription->client;
                $client->user->profile->lastname = trim($data['lastname']);
                $client->user->profile->firstname = trim($data['firstname']);
                $client->user->profile->patronymic = trim($data['patronymic']);
                $client->user->profile->phone = $data['phone'];
                $client->user->profile->email = trim($data['email']);
                $client->user->profile->birthdate = $data['birth_date'] ?? false ? Carbon::parse($data['birth_date']) : null;
                $client->user->profile->save();

                // update subscription status
                $subscription->setStatus(SubscriptionStatus::filled, false);
                $subscription->save();
            });
        } catch (Exception $exception) {
            Log::error($exception->getMessage());
            return APIResponse::error('Возникла ошибка.');
        }

        // response success
        return APIResponse::success('Ваша заявка отправлена.');
    }

    public function preview(?string $subscriptionKey)
    {
        if (empty($subscriptionKey)) {
            abort(404);
        }

        try {
            $subscriptionId = Crypt::decrypt($subscriptionKey);
        } catch (Exception $exception) {
            abort(404);
        }

        /** @var Subscription|null $subscription */
        $subscription = Subscription::query()->where('id', $subscriptionId)->with(['client', 'clientWard', 'service'])->first();

        // make dummy contract
        $contract = new SubscriptionContract();
        $contract->contractData = new SubscriptionContractData();

        $contract->contractData->lastname = '____________';
        $contract->contractData->firstname = '____________';
        $contract->contractData->patronymic = '____________';
        $contract->contractData->phone = '____________';
        $contract->contractData->email = '____________';

        $contract->contractData->passport_serial = '____________';
        $contract->contractData->passport_number = '____________';
        $contract->contractData->passport_place = '____________';
        $contract->contractData->passport_date = null;
        $contract->contractData->passport_code = '____________';
        $contract->contractData->registration_address = '____________';

        $contract->subscription_id = $subscription->id;

        $pdf = SubscriptionContractPdf::generate($contract);

        return response($pdf, 200, [
            'Cache-Control' => 'public',
            'Content-Transfer-Encoding' => 'Binary',
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"договор.pdf\"",
        ]);
    }
}
