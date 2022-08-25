<?php

namespace App\Http\Controllers\Leads;

use App\Http\APIResponse;
use App\Http\Controllers\ApiEditController;
use App\Http\Controllers\Leads\Helpers\LeadSession;
use App\Models\Clients\ClientWard;
use App\Models\Dictionaries\ClientWardStatus;
use App\Models\Dictionaries\SubscriptionContractStatus;
use App\Models\Dictionaries\SubscriptionStatus;
use App\Models\Subscriptions\Subscription;
use App\Models\Subscriptions\SubscriptionContract;
use App\Models\User\User;
use App\Scopes\ForOrganization;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LeadContractController extends ApiEditController
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
            ->with(['service'])
            ->tap(new ForOrganization($organizationId))
            ->where('id', $subscriptionId)
            ->first();

        if ($subscription === null) {
            return APIResponse::error('Ошибка параметров.');
        }
        if (!$subscription->hasStatus(SubscriptionStatus::new)) {
            return APIResponse::error('Форма уже заполнена.');
        }

        /** @var Subscription $subscription */

        $data = $this->getData($request);

        // validate
        $rules = [
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
            'discount' => 'nullable',
        ];
        $titles = [
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
            'discount' => 'Основание для льготы',
        ];
        if ($errors = $this->validate($data, $rules, $titles)) {
            return APIResponse::validationError($errors);
        }

        try {
            DB::transaction(static function () use ($subscription, $data) {
                // add contract
                $contract = new SubscriptionContract();
                $contract->subscription_id = $subscription->id;
                $contract->discount_id = $data['discount'];
                $contract->setStatus(SubscriptionContractStatus::draft, false);
                $contract->save();

                // fill contract data
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

                // TODO update client and ward data

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
}
